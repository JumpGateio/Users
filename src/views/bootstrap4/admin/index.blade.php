@section('title')
  Admin Dashboard
@endsection

<div class="row">
  <div class="col-md-4">
    {!! HTML::adminTile('background-success', 'text-black', 'fa fa-4x fa-users', $userCount) !!}
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-4">
    <div class="table-responsive" style="min-height: calc(100vh - 259px);">
      <table class="table table-striped table-hover">
        <thead class="background-400">
          <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Status</th>
            <th class="text-right">
              <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-external-link"></i>
              </a>
            </th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->status->label }}</td>
              <td class="text-right">
                @include('admin.partials.users.actions', ['iconOnly' => true])
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6">No users found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
