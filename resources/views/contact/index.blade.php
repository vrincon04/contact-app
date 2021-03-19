@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Contacts List') }}</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Birthday') }}</th>
                                    <th scope="col">{{ __('Phone Number') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td scope="row">
                                            <a href="{{ route('contact.show', $contact) }}">{{ $contact->id }}</a>
                                        </td>
                                        <td scope="row">{{ $contact->name }}</td>
                                        <td scope="row">{{ $contact->birthday }}</td>
                                        <td scope="row">{{ $contact->phone }}</td>
                                        <td scope="row">{{ $contact->email }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        {!! $contacts->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
