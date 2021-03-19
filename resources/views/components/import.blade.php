<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
    {{ __('CSV Import Contacts') }}
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('CSV Import Contacts') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-import-file" name="form-import-file" action="{{ route('file.upload') }}" method="POST" enctype="multipart/form-data" >
                    @csrf

                    <div class="form-group">
                        <input type="file" name="file" id="file" required accept=".csv" />
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="hasHeader" name="hasHeader" checked />
                        <label class="form-check-label" for="hasHeader">
                            {{ __('File contains header row?') }}
                        </label>
                    </div>

                    <br/>

                    <h5 class="text-info">{{ __('You must select the value of the columns') }}</h5>

                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light">
                                <th scope="col">A</th>
                                <th scope="col">B</th>
                                <th scope="col">C</th>
                                <th scope="col">E</th>
                                <th scope="col">D</th>
                                <th scope="col">F</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                @for($i = 0; $i < 6; $i++)
                                    <td scope="row">
                                        <select class="custom-select" name="columns[]" required>
                                            <option selected>{{ __('Please select') }}</option>
                                            @foreach($columns as $key => $value)
                                                <option value="{{ $key }}">{{ ucfirst(join(' ', explode('_', $value))) }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                        onclick="event.preventDefault();
                            document.getElementById('form-import-file').submit();">
                    <i class="bi bi-cloud-arrow-up"></i>
                    {{ __('Upload') }}
                </button>
            </div>
        </div>
    </div>
</div>
