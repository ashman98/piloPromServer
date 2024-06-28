<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Session;
use App\Models\User;
use Closure;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use http\Exception\RuntimeException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPUnit\Runner\ErrorException;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'userMutation',
        'description' => 'A mutation for user registration and login'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'name' => ['name' => 'name', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::nonNull(Type::string())],
            'password' => ['name' => 'password', 'type' => Type::nonNull(Type::string())],
            'password_confirmation' => ['name' => 'password_confirmation', 'type' => Type::nonNull(Type::string())],
            'role' => ['name' => 'role', 'type' => Type::string()],
            'action' => ['name' => 'action', 'type' => Type::nonNull(Type::string())], // 'register' or 'login'
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
//            'id' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function resolve($root, array $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $request = new Request($args);

        if ($args['action'] === 'register') {
            return $this->register($request);
        } elseif ($args['action'] === 'login') {
            return $this->login($request);
        } elseif ($args['action'] === 'refresh') {
            return $this->refresh($request);
        }

        throw new \Exception('Invalid action');
    }

    protected function register(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|string|exists:roles,name'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_token' => Str::random(80),
            ]);

            $role = Role::findByName($request->role);
            $user->assignRole($role);
            DB::commit();

            return $user;
        }catch (\Nuwave\Lighthouse\Exceptions\ValidationException $e){
            DB::rollBack();
            throw new \GraphQL\Error\Error('Registration failed: ' . $e->getMessage());
        }
    }

    protected function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::guard('api')->user();

            $accessToken =  Str::random(60);
            $refreshToken = Str::random(80);

            Session::create([
                'user_id' => $user->id,
                'access_token' => $accessToken,
            ]);

            Session::create([
                'user_id' => $user->id,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
            ]);

            $user = User::with(['sessions' => function ($query) {
                $query->latest()->take(1); // Fetch only the latest post
            }])->get();
            return $user;
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    protected function refresh(Request $request)
    {
        try {
            $token = $request->get('token');


                $newToken = JWTAuth::refresh($token);
                return ['email' => $newToken];

         }catch (Error $e){
            throw new \GraphQL\Error\Error('Registration failed: ' . $e->getMessage());
        }
    }
}
