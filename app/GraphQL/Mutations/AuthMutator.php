<?php

namespace App\GraphQL\Mutations;

use App\Mail\SignUp;
use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class AuthMutator
{
    public function register($root, array $args)
    {
        DB::beginTransaction();

        try {
            if ($args['password'] !== $args['password_confirmation']) {
                throw new \Exception('Password confirmation is not right!');
            }
            $user = User::create([
                    'name' => $args['name'],
                    'email' => $args['email'],
                    'password' => Hash::make($args['password']),
                    'state' => $args['state'],
                    'address' => $args['address'],
                    'city' => $args['city'],
                    'surname' => $args['surname'],
                    'phone' => $args['phone'],
                    'zip' => $args['zip'],
                ]);
            $role = Role::findByName($args['role']);
            $user->assignRole($role);


            DB::commit();
            return [
                "user" => [
                    "id"  =>  $user->id
                ],
                "response" => [
                    "code" => '201',
                    "status" => "success",
                    "message" => "User created!"
                    ]
                ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "response" => [
                    "code" => 400,
                    "status" => "error",
                    "message" => $e->getMessage()
                ]
            ];
        }
    }

    public function login($root, array $args,  \Nuwave\Lighthouse\Execution\HttpGraphQLContext $context)
    {
        $request = $context->request();

        $credentials = ['email' => $args['email'], 'password' => $args['password']];
        if (!Auth::attempt($credentials)) {
            throw new \Exception('Invalid credentials.');
        }

        $user = Auth::user();


        $session =
            new Session([
            'id' => session()->getId(),
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'payload' => json_encode(session()->all()),
            'last_activity' => time(),
        ]);
        $session->save();
        $accessToken = $user->createToken('access_token');

        return [
            "user" => ['id' =>  $user->getAuthIdentifier()],
            'access_token' => $accessToken->plainTextToken,
            "response" => [
                "code" => 200,
                "status" => "success",
                "message" => "Successful Auth!"
            ]
        ];
    }

    /**
     * Handle the logout mutation.
     *
     * @param  null  $_
     * @param  array  $args
     * @return array
     */
    public function logout($_, array $args)
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return [
            "code" => 200,
            "status" => "success",
            "message" => "Logout success!"
        ];
    }

    public function refreshToken($root, array $args)
    {
        if(Auth::check())
        {

        }

//        $credentials = ['email' => $args['input']['email'], 'password' => $args['input']['password']];
//        if (!Auth::attempt($credentials)) {
//            throw new \Exception('Invalid credentials.');
//        }

        $user = Auth::user();
        $user->currentAccessToken()->delete();

//        $accessToken = $request->user()->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.ac_expiration')));
        $accessToken = $user->createToken('access_token');

        return [
            'response' => [
                "code" => 200,
                "status" => "success",
                "message" => "Token refreshed successful"
            ],
            'access_token' => $accessToken->plainTextToken,
        ];
    }

    public function emailVerification($root, array $args,  \Nuwave\Lighthouse\Execution\HttpGraphQLContext $context)
    {

//        $request->session()->put('email', 'value@mail.ru');
        try {
//            throw new \Exception(json_encode($request->session()->all()));
            return "s";
        }catch (\Exception $e){
            throw new \Exception($e->getMessage());
        }

    }

    public function sendEmailVerifyNotice($root, array $args,  \Nuwave\Lighthouse\Execution\HttpGraphQLContext $context)
    {
        try {
            $user = User::where('id', $args['user_id'])->first();
            $verifyCode  = array_map('intval', str_split(random_int(100000, 999999)));
            Mail::to($user->email)->send(new SignUp($user->name,$verifyCode));

            return [
                "status" => "success",
                "code" => 200,
                "message" => "Verification notice success sent"
            ];
        }catch (\Exception $e){
            return [
                "code" => 400,
                "status" => "error",
                "message" => $e->getMessage()
            ];
        }

    }
}
