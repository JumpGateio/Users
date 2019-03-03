@section('title')
  Admin Dashboard
@endsection

<div class="row">
  <div class="col-md-3">
    {!! HTML::adminTile('background-success', 'text-black', 'fa fa-4x fa-users', $userCount) !!}
  </div>
</div>

<div class="row mt-2">
  <div class="col-md-4">
    <div class="table-responsive">
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
                <div class="btn-group">
                  @if ($user->status_id === 3)
                    <a href="{{ route('admin.users.unblock', [$user->id]) }}" class="btn btn-sm btn-outline-success">
                      <i class="fa fa-unlock"></i>
                    </a>
                  @else
                    <a href="{{ route('admin.users.block', [$user->id]) }}" class="btn btn-sm btn-outline-warning">
                      <i class="fa fa-lock"></i>
                    </a>
                  @endif
                  <a href="{{ route('admin.users.delete', [$user->id]) }}" class="btn btn-sm btn-outline-danger">
                    <i class="fa fa-trash"></i>
                  </a>
                </div>
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
