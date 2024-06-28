<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class CreateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createUser',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'name' => ['name' => 'name', 'type' => Type::nonNull(Type::string())],
            'email' => ['name' => 'email', 'type' => Type::nonNull(Type::string())],
            'password' => ['name' => 'password', 'type' => Type::nonNull(Type::string())],
        ];
    }

//    protected function rules(array $args = []): array
//    {
//        return [
////            'id' => ['required'],
//            'email' => ['required', 'email'],
//            'password' => $args['id'] !== 1337 ? ['required'] : [],
//        ];
//    }
    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $user = new User([
            'name' =>  $args['name'],
            'email' =>  $args['email'],
            'password' =>   Hash::make($args['password']),
        ]);
        $user->save();

        return $user;

//        $request = new Request([
//            'name' => $args['name'],
//            'email' => $args['email'],
//            'password' => $args['password'],
//            'password_confirmation' => $args['password_confirmation'],
//        ]);
//
//        return $this->authController->register($request);
    }
}
