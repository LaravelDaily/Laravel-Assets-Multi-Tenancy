@can($permissionPrefix . 'show')
    <a class="btn btn-sm btn-primary" href="{{ route('admin.' . $crudRoutePart . '.show', $row->id) }}">
        View
    </a>
@endcan

@can($permissionPrefix . 'edit')
<a class="btn btn-sm btn-info" href="{{ route('admin.' . $crudRoutePart . '.edit', $row->id) }}">
    Edit
</a>
@endcan

@can($permissionPrefix . 'delete')
    @if ($crudRoutePart === 'tenants')
        <a class="btn btn-sm btn-info" href="{{ route('admin.tenants.suspend', $row->id) }}">
            @if ($row->is_suspended)
                Resume
            @else
                Suspend
            @endif
        </a>
    @endif

    <form action="{{ route('admin.' . $crudRoutePart . '.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" class="btn btn-sm btn-danger" value="Delete">
    </form>
@endcan
