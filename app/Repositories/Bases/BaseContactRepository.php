<?php


namespace App\Repositories\Bases;


use App\Helpers\Bases\BaseCreditCardHelper;
use App\Models\Contact;
use App\Repositories\Contracts\IUserPagination;

abstract class BaseContactRepository implements IUserPagination
{
    /**
     * Create new Contact.
     * @param array $data
     * @param BaseCreditCardHelper $creditCardHelper
     * @return Contact|null
     */
    abstract public function create(array $data, BaseCreditCardHelper $creditCardHelper): ?Contact;

    /**
     * @param $id
     * @return Contact|null
     */
    abstract public function getById($id): ?Contact;
}
