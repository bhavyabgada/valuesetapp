<!-- Button trigger modal -->
<div class="row">
    <div class="col-auto">
        <button type="button" class="btn btn-dark btn-sm" data-toggle="modal" data-target="#view{{ $row->id}}">
            <i class="fa fa-eye"></i>
        </button>
    </div>
    <div class="col-auto">
        @php
            $valueset_ids = session('compare_valueset_ids');
            $show_add_to_compare = true;
            if($valueset_ids){
                if ((array_search ( $row->id, $valueset_ids)) !== false) {
                    $show_add_to_compare = false;
                } else {
                    $show_add_to_compare = true;
                }
            }
        @endphp
        <a href="#" id="add_to_compare_btn_{{ $row->id}}" data-row-id="{{$row->id}}" data-add-tocompare-url="{{ route('valueset.add.compare')}}" class="btn btn-sm btn-success add_to_compare_btn" title="Add to compare" style="display:{{$show_add_to_compare ? 'block' : 'none'}}"><i class="fa fa-plus"></i> Add to compare</a>
        <a href="#" id="remove_compare_btn_{{ $row->id}}" data-row-id="{{$row->id}}" data-remove-compare-url="{{ route('valueset.remove.compare')}}" class="btn btn-sm btn-danger remove_compare_btn" title="Remove from compare" style="display:{{$show_add_to_compare ? 'none' : 'block'}}"><i class="fa fa-trash"></i> Remove from compare</a>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="view{{ $row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width:765px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ $row->value_set_name}} ({{ $row->value_set_id}})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow:auto;margin-right:15px;">
                <div class="form-group">
                    <label for="search">Search</label>
                    <x-adminlte-input class="form-control" id="search_keyword_medi_{{ $row->id}}" name="search_keyword_medi_{{ $row->id}}" placeholder="Enter Name" />

                </div>
                <h5 class="modal-title">Medications:</h5>
                <table class="table table-bordered yajra-datatable-view-valueset{{ $row->id}}">
                    <thead>
                        <tr>
                            {{-- <th>
                                <input type="checkbox" id="selectAll" /></th>  --}}
                            {{-- <th>ID</th>  --}}
                            <th>Medicine Name</th>
                            <th>Generic Name</th>
                            <th>Route</th>
                            <th>Outpatients</th>
                            <th>Inpatients</th>
                            <th>Patients</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        var dataTableView = $('.yajra-datatable-view-valueset{{ $row->id}}').DataTable({
            processing: true,
            serverSide: true,
            pageLength: 10,
            searching: false,
            'ajax': {
                'url': "{{ route('valueset.medication.list') }}",
                'data': function(data) {
                    var valueset_id = {{ $row->id}};
                    // Append to data
                    data.valueset_id = valueset_id;

                    // Read values
                    var search_keyword = $('#search_keyword_medi_{{ $row->id}}').val();
                    // Append to data
                    data.search_keyword = search_keyword;

                }
            },
            columns: [
                {
                    data: 'medname',
                    name: 'medname'
                },
                {
                    data: 'simple_generic_name',
                    name: 'simple_generic_name'
                },
                {
                    data: 'route',
                    name: 'route'
                },
                {
                    data: 'outpatients',
                    name: 'outpatients'
                },
                {
                    data: 'inpatients',
                    name: 'inpatients'
                },
                {
                    data: 'patients',
                    name: 'patients'
                },
            ]
        });


        $('#search_keyword_medi_{{ $row->id}}').keyup(function() {
            dataTableView.draw();
        });

        $('#add_to_compare_btn_{{ $row->id}}').on('click', function(e){
            e.preventDefault();
            var valueset_id = $(this).data('row-id');
            var url = $(this).data('add-tocompare-url');
            axios.post(url, {
                valueset_id: valueset_id
                })
                .then(response => {
                    // Handle success response
                    $('.overlay-with-loader').hide();
                    if (response.data.status === 'success') {
                        var success_messages = '';
                        if (Array.isArray(response.data.message)) {
                            $.each(response.data.message, function(Index, Value) {
                                if (success_messages === '') {
                                    success_messages += Value;
                                } else {
                                    success_messages += '<br/>' + Value;
                                }
                            });
                        } else {
                            success_messages = response.data.message;
                        }
                        $('#success-container').html(success_messages).show('slow');
                        $(this).hide();
                        $('#remove_compare_btn_'+valueset_id).show();
                    } else {
                        var messages = '';
                        if (Array.isArray(response.data.message)) {
                            $.each(response.data.message, function(Index, Value) {
                                if (messages === '') {
                                    messages += Value;
                                } else {
                                    messages += '<br/>' + Value;
                                }
                            });
                        } else {
                            messages = response.data.message;
                        }
                        $('#error-container').html(messages).show('slow');
                    }
                    setTimeout(function() {
                        $('#success-container').hide('slow');
                        $('#error-container').hide('slow');
                    }, 5000);
                })
                .catch(error => {
                    console.error(error);
                });
        });

        $('#remove_compare_btn_{{ $row->id}}').on('click', function(e){
            e.preventDefault();
            var valueset_id = $(this).data('row-id');
            var url = $(this).data('remove-compare-url');
            axios.post(url, {
                valueset_id: valueset_id
                })
                .then(response => {
                    // Handle success response
                    $('.overlay-with-loader').hide();
                    if (response.data.status === 'success') {
                        var success_messages = '';
                        if (Array.isArray(response.data.message)) {
                            $.each(response.data.message, function(Index, Value) {
                                if (success_messages === '') {
                                    success_messages += Value;
                                } else {
                                    success_messages += '<br/>' + Value;
                                }
                            });
                        } else {
                            success_messages = response.data.message;
                        }
                        $('#success-container').html(success_messages).show('slow');
                        $(this).hide();
                        $('#add_to_compare_btn_'+valueset_id).show();
                    } else {
                        var messages = '';
                        if (Array.isArray(response.data.message)) {
                            $.each(response.data.message, function(Index, Value) {
                                if (messages === '') {
                                    messages += Value;
                                } else {
                                    messages += '<br/>' + Value;
                                }
                            });
                        } else {
                            messages = response.data.message;
                        }
                        $('#error-container').html(messages).show('slow');
                    }
                    setTimeout(function() {
                        $('#success-container').hide('slow');
                        $('#error-container').hide('slow');
                    }, 5000);
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
