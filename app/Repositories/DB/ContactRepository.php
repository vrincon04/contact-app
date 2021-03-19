<?php


namespace App\Repositories\DB;


use App\Helpers\Bases\BaseCreditCardHelper;
use App\Models\Contact;
use App\Repositories\Bases\BaseContactRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ContactRepository extends BaseContactRepository
{
    /**
     * Get all contacts by user
     * @param int $perPage
     * @return LengthAwarePaginator|null
     */
    public function getByUserWithPaginate($perPage = 10): ?LengthAwarePaginator
    {
        return Contact::where('user_id', auth()->id())->paginate($perPage);
    }


    /**
     * @param $id
     * @return Contact|null
     */
    public function getById($id): ?Contact
    {
        return Contact::findOrFail($id);
    }

    /**
     * Create new Contact.
     * @param array $data
     * @param BaseCreditCardHelper $creditCardHelper
     * @return Contact|null
     */
    public function create(array $data, BaseCreditCardHelper $creditCardHelper): ?Contact
    {
        $contact = new Contact([
            'name' => $data['name'],
            'birthday' => $data['birthday'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'credit_card' => $creditCardHelper->get(),
            'brand' => $creditCardHelper->getBranch(),
            'email' => $data['email'],
            'user_id' => $data['user_id']
        ]);

        try {
            $contact->save();
            return $contact;
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return null;
        }
    }
}
