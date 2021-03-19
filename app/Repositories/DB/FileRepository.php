<?php


namespace App\Repositories\DB;


use App\Models\File;
use App\Repositories\Bases\BaseFileRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class FileRepository extends BaseFileRepository
{

    public function getByUserWithPaginate($perPage = 10): ?LengthAwarePaginator
    {
        return File::where('user_id', auth()->id())->paginate($perPage);
    }

    public function getById($id): File
    {
        return File::findOrFail($id);
    }

    public function create(array $data): ?File
    {
        $file = new File([
            'filename' => $data['filename'],
            'path' => $data['path'],
            'size' => $data['size'],
            'headers' => $data['headers']
        ]);

        try {
            $file->save();
            return $file;
        } catch (\Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return null;
        }
    }
}
