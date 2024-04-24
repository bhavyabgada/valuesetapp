@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Upload Medication CSV</h1>
@stop

@section('content')
    @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title"></h3>
        </div>

        <div class="card-body">
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
@stop
