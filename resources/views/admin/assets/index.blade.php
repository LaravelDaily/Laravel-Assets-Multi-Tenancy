@extends('layouts.app')

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Asset management
                    </div>

                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @can('asset_management_create')
                            <div class="form-group">
                                <a href="{{ route('admin.assets.create') }}" class="btn btn-success">Create asset</a>
                            </div>
                        @endcan
                        <table class=" table table-bordered table-striped table-hover ajaxTable datatable">
                            <thead>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    Name
                                </th>
                                <th>
                                    Serial Number
                                </th>
                                <th>
                                    Price
                                </th>
                                <th>
                                    Warranty Expiry Date
                                </th>
                                <th>
                                    Asset Group
                                </th>
                                <th>
                                    Asset Sub-group
                                </th>
                                <th>
                                    &nbsp;
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        let table = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.assets.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'serial_number', name: 'serial_number' },
                { data: 'price', name: 'price' },
                { data: 'warranty_expiry_date', name: 'warranty_expiry_date' },
                { data: 'group', name: 'group' },
                { data: 'sub_group', name: 'sub_group' },
                { data: 'actions', name: '' }
            ],
            orderCellsTop: true,
            order: [[ 0, 'desc' ]],
            pageLength: 100,
        });
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });

    });

</script>
@endsection
