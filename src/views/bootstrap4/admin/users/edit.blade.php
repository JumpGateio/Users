@section('title')
  Edit User
@endsection

<div class="p-2">
  <p class="lead">Edit: {{ $user->email }}</p>
  <div class="hr-divider"></div>
  <div class="card-body p-1 py-3">
    {!! Form::open() !!}
    <div class="form-group">
      {!! Form::label('user[email]', 'Email Address') !!}
      {!! Form::text('user[email]', $user->email, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
    </div>
    <div class="form-group row">
      <div class="col-md-3">
        {!! Form::label('details[display_name]', 'Display Name') !!}
        {!! Form::text('details[display_name]', $user->display_name, ['class' => 'form-control', 'placeholder' => 'Display Name']) !!}
      </div>
      <div class="col-md-3">
        {!! Form::label('details[first_name]', 'First Name') !!}
        {!! Form::text('details[first_name]', $user->first_name, ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
      </div>
      <div class="col-md-3">
        {!! Form::label('details[middle_name]', 'Middle Name') !!}
        {!! Form::text('details[middle_name]', $user->middle_name, ['class' => 'form-control', 'placeholder' => 'Middle Name']) !!}
      </div>
      <div class="col-md-3">
        {!! Form::label('details[last_name]', 'Last Name') !!}
        {!! Form::text('details[last_name]', $user->last_name, ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
      </div>
    </div>
    <div class="form-group">
      {!! Form::label('status_id', 'Status') !!}
      {!! Form::select('status_id', $statuses, $user->status_id, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('roles', 'Select Roles') !!}
      {!! Form::select('roles[]', $roles, $user->roles->id->toArray(), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
    </div>
    <div class="form-group">
      {!! Form::label('failed_login_attempts', 'Failed Login Attempts') !!}
      {!! Form::text('failed_login_attempts', $user->failed_login_attempts, ['class' => 'form-control', 'placeholder' => 'Display Name']) !!}
    </div>
    <div class="form-group">
      {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
      <a href="{{ route('admin.users.index') }}" class="btn btn-link">Cancel</a>
    </div>
    {!! Form::close() !!}
  </div>
</div>
