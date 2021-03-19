<?php


namespace App\Repositories\Contracts;


use Illuminate\Pagination\LengthAwarePaginator;

interface IUserPagination
{
    /**
     * Get all contacts by user with pagination
     * @param int $perPage
     * @return LengthAwarePaginator|null
     */
    public function getByUserWithPaginate($perPage = 10): ?LengthAwarePaginator;
}
