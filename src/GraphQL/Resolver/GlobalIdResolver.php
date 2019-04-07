<?php

namespace App\GraphQL\Resolver;

use App\Repository\{BoardRepository,
    CategoryRepository,
    TeamRepository,
    TicketRepository,
    UserRepository};
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;

class GlobalIdResolver implements ResolverInterface
{

    private $userRepository;
    private $boardRepository;
    private $categoryRepository;
    private $teamRepository;
    private $ticketRepository;

    public function __construct(
        UserRepository $userRepository,
        BoardRepository $boardRepository,
        CategoryRepository $categoryRepository,
        TeamRepository $teamRepository,
        TicketRepository $ticketRepository
    )
    {

        $this->userRepository = $userRepository;
        $this->boardRepository = $boardRepository;
        $this->categoryRepository = $categoryRepository;
        $this->teamRepository = $teamRepository;
        $this->ticketRepository = $ticketRepository;
    }

    public function __invoke(string $id)
    {
        $node = $this->userRepository->find($id);

        if (!$node) {
            $node = $this->boardRepository->find($id);
        }
        if (!$node) {
            $node = $this->categoryRepository->find($id);
        }
        if (!$node) {
            $node = $this->teamRepository->find($id);
        }
        if (!$node) {
            $node = $this->ticketRepository->find($id);
        }

        if (!$node) {
            throw new UserError('Could not find node!');
        }


        return $node;
    }

}