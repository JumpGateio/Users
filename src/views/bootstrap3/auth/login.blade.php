@extends('layout')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Login</h3>
          </div>
          <div class="panel-body">
            {!! Form::open(['class' => 'form-horizontal']) !!}
            <div class="form-group">
              {!! Form::label('email', 'Email', ['class' => 'col-sm-4 col-md-2 control-label']) !!}
              <div class="col-sm-8 col-md-10">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('password', 'Password', ['class' => 'col-sm-4 col-md-2 control-label']) !!}
              <div class="col-sm-8 col-md-10">
                {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-4 col-sm-8 col-md-offset-2 col-md-10">
                <input type="submit" value="Login" class="btn btn-primary">
                <a href="{{ route('auth.register') }}" class="btn btn-link">
                  Register
                </a>
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
