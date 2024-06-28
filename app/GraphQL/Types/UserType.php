<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\GraphQL\Privacy\UserPrivacy;
use App\Models\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user',
        'model' => User::class
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user',
                // Use 'alias', if the database column is different from the type name.
                // This is supported for discrete values as well as relations.
                // - you can also use `DB::raw()` to solve more complex issues
                // - or a callback returning the value (string or `DB::raw()` result)
                'alias' => 'user_id',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of user',
            ],
            'password' => [
                'type' => Type::string(),
                'description' => 'The password of user',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user',
                'resolve' => function($root, array $args) {
                    return strtolower($root->email);
                },
                'privacy' => UserPrivacy::class
            ],
            // Uses the 'getIsMeAttribute' function on our custom User model
//            'isMe' => [
//                'type' => Type::boolean(),
//                'description' => 'True, if the queried user is the current user',
//                'selectable' => false, // Does not try to query this from the database
//            ]
        ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
//    protected function resolveEmailField($root, array $args)
//    {
//        return strtolower($root->email);
//    }
}
