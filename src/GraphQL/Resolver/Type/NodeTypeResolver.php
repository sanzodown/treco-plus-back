<?php

namespace App\GraphQL\Resolver\Type;

use App\Entity\Board;
use App\Entity\Category;
use App\Entity\Team;
use App\Entity\Ticket;
use App\Entity\User;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Error\UserError;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

class NodeTypeResolver implements ResolverInterface
{

    private $resolver;

    public function __construct(TypeResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function __invoke($node)
    {
        if ($node instanceof User) {
            return $this->resolver->resolve('User');
        }
        if ($node instanceof Team) {
            return $this->resolver->resolve('Team');
        }
        if ($node instanceof Ticket) {
            return $this->resolver->resolve('Ticket');
        }
        if ($node instanceof Board) {
            return $this->resolver->resolve('Board');
        }
        if ($node instanceof Category) {
            return $this->resolver->resolve('Category');
        }

        throw new UserError("Can't resolve node type!");
    }

}