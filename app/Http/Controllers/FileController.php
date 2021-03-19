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
}
