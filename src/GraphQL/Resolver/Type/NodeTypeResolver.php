<?php

namespace App\GraphQL\Resolver\Type;

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

        throw new UserError("Can't resolve node type!");
    }

}