<?php

namespace App\GraphQL\Mutation\User;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginUserMutation implements MutationInterface
{
    private $encoder;
    private $tokenManager;
    private $repository;
    private $authenticationSuccessHandler;

    public function __construct(UserPasswordEncoderInterface $encoder,
                                UserRepository $repository,
                                AuthenticationSuccessHandler $authenticationSuccessHandler,
                                JWTTokenManagerInterface $tokenManager)
    {
        $this->encoder = $encoder;
        $this->tokenManager = $tokenManager;
        $this->repository = $repository;
        $this->authenticationSuccessHandler = $authenticationSuccessHandler;
    }
    public function __invoke(Argument $args)
    {
        [$email, $password] = [$args->offsetGet('email'), $args->offsetGet('password')];
        if ($viewer = $this->repository->findOneBy(compact('email'))) {
            if ($this->encoder->isPasswordValid($viewer, $password)) {
                $token = $this->tokenManager->create($viewer);
                $response = $this->authenticationSuccessHandler->handleAuthenticationSuccess($viewer, $token)
                    ->getContent();
                $refreshToken = json_decode($response, true)['refresh_token'];
                return compact('token', 'refreshToken');
            }
            throw new UserError('Bad credentials.');
        }
        throw new UserError('Bad credentials.');
    }
}