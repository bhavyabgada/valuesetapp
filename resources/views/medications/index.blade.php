@extends('adminlte::page')

@include('includes.datatable-axios-files')

@section('title', 'Dashboard')

@section('content_header')
<h1>Medications</h1>
<small>Manage Medicaions</small>
@stop

@section('content')
<div class="card card-dark">
    <div class="card-header">
        <h3 class="card-title"></h3>
        <div class="card-tools">
            <a href="{{route('medications.create')}}" class="btn btn-dark"><i class="fas fa-file-upload"></i> Upload Medications</a>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" id="search-form" role="form">

            <div class="form-group">
                <label for="search">Search</label>
                <x-adminlte-input class="form-control" id="search_keyword" name="search_keyword" placeholder="Enter Name" />

            </div>

            <!--<button type="submit" class="btn btn-primary">Filter</button>-->

            <!--<div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>-->
        </form>
        <table class="table table-bordered yajra-datatable">
            <thead>
                <tr>
                    {{-- <th>
                        <input type="checkbox" id="selectAll" /></th>  --}}
                    {{-- <th>ID</th>  --}}
                    <th>Medication Name</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript">
    $(function() {

        var dataTable = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            searching: false,
            'ajax': {
                'url': "{{ route('medications.list') }}",
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
                    data: 'medname',
                    name: 'medname'
                },
                {
                    data: 'created_at',
                    name: 'Created Date'
                },
                /*{
                    data: 'action',
                    name: 'action',
                },*/
            ]
        });

        $('#search_keyword').keyup(function() {
            dataTable.draw();
        });

        /*
            // select all rows
            $('#selectAll').on('change', function(){
                let groupCheck = Array.from(document.getElementsByClassName('select_row'));
                //console.log(groupCheck)
                if (this.checked) {
                    groupCheck.forEach(element => {
                        // checking the checkbox
                        element.checked = true;
                        //$('#bulk-action-apply').prop('disabled', false);
                        $('.bulk-action-wrap').show();
                    })
                } else {
                    groupCheck.forEach(element => {
                        // Unchecking the checkbox
                        element.checked = false;
                        $('.bulk-action-wrap').hide();
                    })
                }
            });*/

    });
</script>
@stop
