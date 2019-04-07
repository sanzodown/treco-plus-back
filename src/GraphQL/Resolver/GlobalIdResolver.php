<?php

namespace App\GraphQL\Resolver;

use App\Repository\{FictionChapterCommentRepository,
    FictionChapterRepository,
    FictionCommentRepository,
    FictionRepository,
    UserRepository};
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;

class GlobalIdResolver implements ResolverInterface
{

    private $userRepository;

    public function __construct(
        UserRepository $userRepository
    )
    {

        $this->userRepository = $userRepository;
    }

    public function __invoke(string $id)
    {
        $node = $this->userRepository->find($id);

        if (!$node) {
            throw new UserError('Could not find node!');
        }


        return $node;
    }

}