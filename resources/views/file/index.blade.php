@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-2">
            <div class="col-md-12">
                @include('components.import', ['columns' => $columns])
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ __('Files List') }}</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Size</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($files as $file)
                                    <tr>
                                        <td scope="row">
                                            <a class="btn btn-link" href="{{ route('file.show', $file) }}">{{ $file->id }}</a>
                                        </td>
                                        <td scope="row">{{ $file->filename }}</td>
                                        <td scope="row">{{ (new \App\Enums\FileStatusEnum($file->status))->getInternalName() }}</td>
                                        <td scope="row">{{ $file->size_in_kb }}KB</td>
                                        <td scope="row"><a class="btn btn-link" href="{{ $file->url }}"><i class="bi bi-cloud-arrow-down"></i> Download</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer">
                        {!! $files->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
