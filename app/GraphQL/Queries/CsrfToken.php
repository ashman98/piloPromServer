<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use Illuminate\Support\Facades\Session;

final class CsrfToken
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
//        $request = new HttpReq();
//        throw new \Exception(json_encode(Request::session()->all()));
        return Session::token();
    }
}
