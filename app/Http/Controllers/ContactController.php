<?php

namespace App\Http\Controllers;

use App\Repositories\Bases\BaseContactRepository;

class ContactController extends Controller
{
    private $_repository;

    public function __construct(BaseContactRepository $repository)
    {
        $this->_repository = $repository;
    }

    public function index()
    {
        return view('contact.index')->with('contacts', $this->_repository->getByUserWithPaginate(5));
    }

    public function show($id)
    {
        $contact = $this->_repository->getById($id);
        return view('contact.show')->with('contact', $contact);
    }
}
