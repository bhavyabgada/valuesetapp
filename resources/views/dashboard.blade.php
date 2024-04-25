@extends('adminlte::page')

@include('includes.datatable-axios-files')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
<small>Compare Valuesets</small>
@stop

@section('content')
<div class="card card-dark">
    <div class="card-header">
        <h3 class="card-title"></h3>
        <div class="card-tools">
            <a href="{{route('valueset.compare')}}" class="btn btn-info"><i class="fas fa-columns"></i> Compare Selected ValueSets</a>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col">
                {{--  <form method="POST" id="search-form" role="form">  --}}

                <div class="form-group">
                    <label for="search">Search</label>
                    <x-adminlte-input class="form-control" id="search_keyword" name="search_keyword" placeholder="Enter Name" />
                </div>

                {{--  <div class="form-group">
                    <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="exampleCheckbox">
                    <label class="form-check-label" for="exampleCheckbox">
                        With common medications
                    </label>
                    </div>
                </div>  --}}

                <div class="row justify-content-end">
                    <div class="col-auto">
                        <button class="btn btn-danger" id="clear_filter_btn">Clear Filter</button>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-dark" id="filter_btn">Filter</button>
                    </div>
                </div>

                <!--<div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>-->
                {{--  </form>  --}}
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table table-bordered yajra-datatable">
                    <thead>
                        <tr>
                            {{-- <th>
                                <input type="checkbox" id="selectAll" /></th>  --}}
                            {{-- <th>ID</th>  --}}
                            <th>Id</th>
                            <th>Value Set Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
<script type="text/javascript">
    $(function() {

        var dataTable = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            searching: false,
            'ajax': {
                'url': "{{ route('valueset.compare.list') }}",
                'data': function(data) {
                    // Read values
                    var search_keyword = $('#search_keyword').val();


                    // Append to data
                    data.search_keyword = search_keyword;

                }
            },
            columns: [
                /*{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},*/
                /*  {data: 'id', name: 'ID'},  */
                {
                    data: 'value_set_id',
                    name: 'value_set_id'
                },
                {
                    data: 'value_set_name',
                    name: 'value_set_name'
                },
                {
                    data: 'action',
                    name: 'action',
                },
            ]
        });

        /*$('#search_keyword').keyup(function() {
            dataTable.draw();
        });*/

        $('#filter_btn').on('click', function(e){
            e.preventDefault();
            dataTable.draw();
        });
        $('#clear_filter_btn').on('click', function(e){
            e.preventDefault();
            $('#search_keyword').val('');
            dataTable.draw();
        });

    });
</script>
@endpush
