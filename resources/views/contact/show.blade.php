@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-4">
            <h3 class="my-3">{{ $contact->name }} {{ __('Detail') }}</h3>
            <p><b>{{ __('Address') }}</b></p>
            <p>{{ $contact->address }}</p>
            <h3 class="my-3">Contact Details</h3>
            <ul>
                <li><b>{{ __('Birthday') }}</b>: {{ $contact->birthday }}</li>
                <li><b>{{ __('Phone Number') }}</b>: {{ $contact->phone }}</li>
                <li><b>{{ __('Email') }}</b>: {{ $contact->email }}</li>
                <li><b>{{ __('Credit Card') }}</b>: {{ $contact->brand }} {{ $contact->credit_card }}</li>
            </ul>
        </div>
    </div>
@endsection
