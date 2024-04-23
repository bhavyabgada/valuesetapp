@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Upload Medication CSV</h1>
            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            <form action="{{ route('medications.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group @error('file') has-error @enderror">
                    <label for="file">CSV file</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".csv">
                    @error('file')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Upload</button>
            </form>
        </div>
    </div>
</div>
@endsection
