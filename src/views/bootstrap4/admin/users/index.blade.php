@section('title')
  Users
@endsection

<table id="users-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Email</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
</table>

@section('css')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.css"/>
@endsection
@section('jsInclude')
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4-4.1.1/jq-3.3.1/dt-1.10.18/datatables.min.js"></script>
@endsection
@section('onReadyJs')
  $('#users-table').DataTable({
    serverSide: true,
    processing: true,
    responsive: true,
    ajax:       "{{ route('admin.users.data-table') }}",
    columns:    [
      {name: 'id'},
      {name: 'email'},
      {name: 'action', orderable: false, searchable: false}
    ]
  });
@endsection

<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Roles</th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->role_list }}</td>
          <td class="text-right">
            <div class="btn-group">
              <a href="{{ route('admin.users.delete', [$user->id]) }}" class="btn btn-sm btn-outline-danger">
                Delete
              </a>
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
