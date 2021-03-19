@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-4">
            <h3 class="my-3">{{ __('File Name:') }} {{ $file->filename }}</h3>
            <p><b>{{ __('Status') }}</b></p>
            <p>{{ (new \App\Enums\FileStatusEnum($file->status))->getInternalName() }}</p>
            <h3 class="my-3">{{ __('File Details') }}</h3>
            <ul>
                <li><b>{{ __('Size') }}</b>: {{ $file->size_in_kb }}KB</li>
                <li><b>{{ __('File Path') }}</b>: <a class="btn btn-link" href="{{ $file->url }}"><i class="bi bi-cloud-arrow-down"></i> Download</a></li>
            </ul>
            <h3 class="my-3">{{ __('Errors') }}</h3>
            <pre>
                {{ json_encode($file->errors, JSON_PRETTY_PRINT) }}
            </pre>
        </div>
    </div>
@endsection
