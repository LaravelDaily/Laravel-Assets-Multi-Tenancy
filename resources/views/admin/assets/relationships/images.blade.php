@can('image_management_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.images.create', ['asset_id' => $id]) }}">
                Add image
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        Image management
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Image" style="width: 100%;">
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Image
                </th>
                <th>
                    &nbsp;
                </th>
            </tr>
            </thead>
        </table>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
        let table = $('.datatable-Image').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.images.index', ['asset_id' => $id]) }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'image', name: 'image', sortable: false, searchable: false },
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
