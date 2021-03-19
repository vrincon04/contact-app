<?php


namespace App\Repositories\Bases;


use App\Models\File;
use App\Repositories\Contracts\IUserPagination;

abstract class BaseFileRepository implements IUserPagination
{
    /**
     * Create new Contact.
     * @param array $data
     * @return File|null
     */
    abstract public function create(array $data): ?File;

    /**
     * @param $id
     * @return File
     */
    abstract public function getById($id): File;
    
}
