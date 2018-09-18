@section('title')
  Add User
@endsection

<div class="p-2">
  <p class="lead">Add a new user</p>
  <div class="hr-divider"></div>
  <div class="card-body p-1 py-3">
    {!! Form::open() !!}
    <div class="form-group">
      {!! Form::label('email', 'Email Address') !!}
      {!! Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) !!}
      <p id="emailHelp" class="form-text text-muted">
        This will send an email to the user to allow then to log in.
      </p>
    </div>
    <div class="form-group">
      {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
      <a href="{{ route('admin.users.index') }}" class="btn btn-link">Cancel</a>
    </div>
    {!! Form::close() !!}
  </div>
</div>
