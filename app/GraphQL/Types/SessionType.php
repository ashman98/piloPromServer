<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Role;
use App\Models\Session;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SessionType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Session',
        'description' => 'A session',
        'model' => Session::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::id()),
            ],
            'user_id' => [
                'type' => Type::int(),
            ],
            'access_token' => [
                'type' => Type::string(),
            ],
            'refresh_token' => [
                'type' => Type::string(),
            ],
            'created_at' => [
                'type' =>Type::string(),
            ],
            'user' => [
                'type' => GraphQL::type('User'),
                'description' => 'The owner of the post',
            ],
            'users' => [
                'type' => Type::listOf(GraphQL::type('User')),
                'description' => 'The roles of the user'
            ],
        ];
    }
}
