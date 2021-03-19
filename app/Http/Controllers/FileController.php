<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Imports\ContactImport;
use App\Repositories\Bases\BaseContactRepository;
use App\Repositories\Bases\BaseFileRepository;
use Maatwebsite\Excel\Facades\Excel;

class FileController extends Controller
{
    private $_repository;

    private $_columns = [
        'name',
        'birthday',
        'phone',
        'address',
        'credit_card',
        'email'
    ];

    public function __construct(BaseFileRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function index()
    {
        return view('file.index')->with([
            'columns' => $this->_columns,
            'files' => $this->_repository->getByUserWithPaginate(5)
        ]);
    }

    public function show($id)
    {
        $file = $this->_repository->getById($id);
        return view('file.show')->with('file', $file);
    }

    public function upload(StoreFileRequest $request, BaseContactRepository $repository): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $fields = $request->input('columns');
        $columns = $this->_columns;
        $newHeader = array_flip(array_map(function ($value) use ($columns) {
            return $columns[$value];
        }, $fields));

        $path = $request->file('file')->store('contacts', 's3');

        $file = $this->_repository->create([
            'filename' => basename($path),
            'path' => $path,
            'size' => $request->file('file')->getSize(),
            'headers' => json_encode($newHeader)
        ]);

        Excel::import(new ContactImport($newHeader, auth()->id(), $file->id, $repository), $path,'s3');

        return redirect()->route('file.index');

    }
}
