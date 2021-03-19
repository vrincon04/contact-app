<?php

namespace App\Imports;

use App\Enums\FileStatusEnum;
use App\Helpers\CreditCardHelper;
use App\Http\Requests\StoreContactRequest;
use App\Repositories\Bases\BaseContactRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ContactImport implements ToCollection, ShouldQueue, WithChunkReading, WithStartRow
{
    use Importable;

    private $headers;

    private $user_id;

    private $file_id;

    private $_repository;

    private $rows_fails = 0;

    private $total_rows = 0;

    private $list_errors = [];

    /**
     * ContactImport constructor.
     * @param array $headers
     * @param int $user
     * @param int $file_id
     * @param BaseContactRepository $repository
     */
    public function __construct(array $headers, int $user, int $file_id, BaseContactRepository $repository)
    {
        $this->headers = $headers;
        $this->user_id = $user;
        $this->file_id = $file_id;
        $this->_repository = $repository;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->list_errors;
    }

    /**
     * @return int
     */
    public function getRowsFails(): int
    {
        return $this->rows_fails;
    }

    public function getTotalRows(): int
    {
        return $this->total_rows;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $this->total_rows = (int) $collection->count() - 1;

        $chunks = $collection->chunk(500);

        DB::table('files')
            ->where('id', $this->file_id)
            ->update([
               'status' => FileStatusEnum::PROCESSING
            ]);

        $chunks->each(function ($rows) {
            foreach ($rows as $row)
            {
                $rowTemp = $row->toArray();
                $contact = [
                    'name' => $rowTemp[$this->headers['name']],
                    'birthday' => $rowTemp[$this->headers['birthday']],
                    'phone' => $rowTemp[$this->headers['phone']],
                    'credit_card' => $rowTemp[$this->headers['credit_card']],
                    'address' => $rowTemp[$this->headers['address']],
                    'email' => $rowTemp[$this->headers['email']],
                    'user_id' => $this->user_id
                ];
                $validator = Validator::make($contact, (new StoreContactRequest())->rules());

                if($validator->fails()) {
                    $this->rows_fails++;
                    $this->list_errors[] = [
                        'row' => $rowTemp,
                        'errors' => $validator->errors()
                    ];
                    continue;
                }

                $this->_repository->create($contact, new CreditCardHelper($contact['credit_card']));
            }
        });

        DB::table('files')
            ->where('id', $this->file_id)
            ->update([
                'errors' => json_encode($this->list_errors),
                'status' => ($this->rows_fails > 0) ? FileStatusEnum::FAIL : FileStatusEnum::COMPLETED,
            ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function startRow(): int
    {
        return 2;
    }
}
