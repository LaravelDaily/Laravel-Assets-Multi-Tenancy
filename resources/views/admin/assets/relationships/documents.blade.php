@can('document_management_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.documents.create', ['asset_id' => $id]) }}">
                Add document
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        Document management
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-Document" style="width: 100%;">
            <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>
                    Document
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
        let table = $('.datatable-Document').DataTable({
            processing: true,
            serverSide: true,
            retrieve: true,
            aaSorting: [],
            ajax: "{{ route('admin.documents.index', ['asset_id' => $id]) }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'document', name: 'document', sortable: false, searchable: false },
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
