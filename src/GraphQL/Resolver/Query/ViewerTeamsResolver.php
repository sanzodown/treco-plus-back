<?php

namespace App\GraphQL\Resolver\Query;

use App\Entity\User;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class ViewerTeamsResolver implements ResolverInterface
{
    public function __invoke(Argument $args, User $user)
    {
        // Add your logic when the mutation is called here (e.g database calls and updates).
        // $args is generally an object passed in your Mutation.types.yaml and contains arguments of your mutation.
    }
}
