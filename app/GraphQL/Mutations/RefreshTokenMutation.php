<?php

namespace App\GraphQL\Mutations;

use Closure;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Http\Request;

class RefreshTokenMutation extends Mutation
{
    protected $attributes = [
        'name' => 'refreshToken',
        'description' => 'Refreshes a JWT token'
    ];

    public function type(): Type
    {
        return Type::nonNull(Type::string()); // Return the refreshed token type
    }

    public function args(): array
    {
        return [
            'refresh_token' => ['name' => 'refresh_token', 'type' => Type::nonNull(Type::string())],
        ];
    }

    public function resolve($root, array $args, $context, Closure $getSelectFields)
    {
        try {
            $token = JWTAuth::refresh($args['refresh_token']);
        } catch (TokenInvalidException $e) {
            throw new ValidationException(['refresh_token' => ['The refresh token is invalid.']]);
        }

        return $token;
    }
}
