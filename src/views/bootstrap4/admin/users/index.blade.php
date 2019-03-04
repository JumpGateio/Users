@section('title')
  Users
@endsection

<div class="table-responsive" style="min-height: calc(100vh - 106px);">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Status</th>
        <th>Roles</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->email }}</td>
          <td>
            @if(! is_null($user->deleted_at))
              Deleted
            @else
              {{ $user->status->label }}
            @endif
          </td>
          <td>{{ $user->role_list }}</td>
          <td class="text-right">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
              @if ($user->trashed())
                <a href="{{ route('admin.users.confirm', [$user->id, 'delete', 0]) }}" class="btn btn-sm btn-outline-danger">
                  Restore
                </a>
              @else
                <div class="btn-group mr-2">
                  <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle"
                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                  </button>
                  <div class="dropdown-menu dropdown-menu-right">
                    @foreach ($user->adminActions as $action)
                      <a href="{{ $action->route }}" class="dropdown-item p-2">
                        <i class="fa {{ $action->icon }} text-500"></i>&nbsp;&nbsp;{{ $action->text }}
                      </a>
                    @endforeach
                  </div>
                </div>
                <div class="btn-group">
                  <a href="{{ route('admin.users.edit', [$user->id]) }}" class="btn btn-sm btn-outline-purple">
                    Edit
                  </a>
                  <a href="{{ route('admin.users.confirm', [$user->id, 'delete', 1]) }}" class="btn btn-sm btn-outline-danger">
                    Delete
                  </a>
                </div>
              @endif
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td>No users found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $users->links() }}
