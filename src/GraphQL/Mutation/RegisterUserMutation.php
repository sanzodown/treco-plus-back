<?php

namespace App\GraphQL\Mutation;

use App\Entity\User;
use App\Form\RegisterFormType;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\UserError;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUserMutation implements MutationInterface
{
    private $userPasswordEncoder;
    private $factory;
    private $entity;
    private $tokenManager;
    private $authenticationSuccessHandler;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder, FormFactoryInterface $factory, EntityManagerInterface $entity, JWTTokenManagerInterface $tokenManager, AuthenticationSuccessHandler $authenticationSuccessHandler)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->factory = $factory;
        $this->entity = $entity;
        $this->tokenManager = $tokenManager;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
    }

    public function __invoke(Argument $args)
    {
        [
            $email,
            $username,
            $plainPassword
        ] = [
            $args->offsetGet('email'),
            $args->offsetGet('username'),
            $args->offsetGet('password')];

        $user = new User();
        $form = $this->factory->create(RegisterFormType::class, $user);
        $form->submit(compact('email', 'username', 'plainPassword'), false);
        if (!$form->isValid()) {
            throw new UserError("invalid user");
        }
        $this->entity->persist($user);
        $this->entity->flush();

        $token = $this->tokenManager->create($user);
        $response = $this->authenticationSuccessHandler->handleAuthenticationSuccess($user, $token)
            ->getContent();

        $refreshToken = json_decode($response, true)['refresh_token'];

        return compact('token', 'refreshToken');
    }

}
