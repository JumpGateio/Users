@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">Register</div>
          {!! Form::open(['class' => 'form-horizontal']) !!}
          <div class="panel-body">
            <div class="form-group">
              {!! Form::label('email', 'Email', ['class' => 'col-lg-3 control-label']) !!}
              <div class="col-lg-9">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('display_name', 'Display Name', ['class' => 'col-lg-3 control-label']) !!}
              <div class="col-lg-9">
                {!! Form::text('display_name', null, ['class' => 'form-control']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('password', 'Password', ['class' => 'col-lg-3 control-label']) !!}
              <div class="col-lg-9">
                {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-lg-3 control-label']) !!}
              <div class="col-lg-9">
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
          </div>
          <div class="panel-footer">
            <div class="row">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" value="Register" class="btn btn-primary">
                <a href="{!! route('auth.login') !!}" class="btn btn-link">
                  Login
                </a>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
