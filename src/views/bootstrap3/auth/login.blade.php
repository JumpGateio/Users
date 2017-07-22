@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading">Login</div>
          {!! Form::open(['class' => 'form-horizontal']) !!}
          <div class="panel-body">
            <div class="form-group">
              {!! Form::label('email', 'Email', ['class' => 'col-lg-3 control-label']) !!}
              <div class="col-lg-9">
                {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('password', 'Password', ['class' => 'col-lg-3 control-label']) !!}
              <div class="col-lg-9">
                {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
              </div>
            </div>
          </div>
          <div class="panel-footer">
            <div class="row">
              <div class="col-lg-offset-3 col-lg-9">
                <input type="submit" value="Login" class="btn btn-primary">
                <a href="{!! route('auth.register') !!}" class="btn btn-link">
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
