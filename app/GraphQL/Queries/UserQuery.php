<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\User;
use Illuminate\Support\Facades\Session;

final class UserQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $perPage = $args['first'];
        $page = isset($args['page']) ? $args['page'] : 1;
        $paginator = User::paginate($perPage, ['*'], 'page', $page);

        return [
           "data" => $paginator,
            "paginatorInfo" => [
                "total" => $paginator->total()
            ]
        ];
    }
}
