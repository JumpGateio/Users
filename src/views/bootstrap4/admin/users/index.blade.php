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
            @include('admin.partials.users.actions')
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
