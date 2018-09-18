@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="offset-lg-3 col-lg-6">
        <div class="card">
          <div class="card-header">Register</div>
          <div class="card-body">
            {!! Form::open(['class' => 'form-horizontal']) !!}
            <div class="form-group row">
              {!! Form::label('email', 'Email', ['class' => 'col-3 col-form-label']) !!}
              <div class="col-9">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
            <div class="form-group row">
              {!! Form::label('display_name', 'Display Name', ['class' => 'col-3 col-form-label']) !!}
              <div class="col-9">
                {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group row">
              {!! Form::label('password', 'Password', ['class' => 'col-3 col-form-label']) !!}
              <div class="col-9">
                {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
            <div class="form-group row">
              {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-3 col-form-label']) !!}
              <div class="col-9">
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
          </div>
          <div class="card-footer">
            <input type="submit" value="Register" class="btn btn-primary">
            <a href="{!! route('auth.login') !!}" class="btn btn-link">
              Login
            </a>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
