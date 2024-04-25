@extends('adminlte::page')

@include('includes.datatable-axios-files')

@section('title', 'Dashboard')

@section('content_header')
<h1>ValueSets Comparison</h1>
<small>ValueSets</small>
@stop

@section('content')
<div class="card card-dark">
    <div class="card-header">
        <h3 class="card-title"></h3>
        <div class="card-tools">
            <a href="{{route('dashboard')}}" class="btn btn-info"><i class="fas fa-plus"></i> Add valuesets to compare</a>
        </div>
    </div>

    <div class="card-body">
        @if($valueset_mediactions)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @foreach ($valueset_mediactions as $valueset_name=>$valueset_mediaction)
                            <th>{{$valueset_name}}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($valueset_mediactions as $key=>$valueset_mediaction_column)
                            <td>
                                @foreach ($valueset_mediaction_column as $valueset_mediaction)
                                    <ul>
                                        @foreach ($valueset_mediaction as $medication_key=>$mediaction)
                                        <li class="{{(in_array($medication_key, $common_keys)) ? 'common-medication alert alert-success' : 'different-medication'}}">
                                            <strong>{{$mediaction['label']}} {{$medication_key}}:</strong> {{$mediaction['value']}}
                                        </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @else
            <p>No valueset selected, please select valuesets to compare.</p>
        @endif
    </div>
</div>
@stop

