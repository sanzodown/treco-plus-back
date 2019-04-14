<?php

namespace App\GraphQL\Resolver\Query;

use App\Entity\User;
use App\Repository\TeamRepository;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class TeamBoardsResolver implements ResolverInterface
{
    private $teamRepository;

    public function __construct(TeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function __invoke(Argument $args, User $user)
    {
        $teamId = $args->offsetGet('teamId');
        $team = $this->teamRepository->find($teamId);

        if($team) {
          foreach ($user->getTeams() as $userTeam) {
              if($userTeam->getId() === $team->getId()) {
                  return $team->getBoards();
              }
          }
        }

        return null;
    }

}
