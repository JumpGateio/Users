@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="offset-lg-3 col-lg-6">
        <div class="panel panel-default">
          <div class="card">
            <div class="card-header">Login</div>
            <div class="card-block">
              {!! Form::open(['class' => 'form-horizontal']) !!}
              <div class="form-group row">
                {!! Form::label('email', 'Email', ['class' => 'col-3 col-form-label']) !!}
                <div class="col-9">
                  {!! Form::text('email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
              </div>
              <div class="form-group row">
                {!! Form::label('password', 'Password', ['class' => 'col-3 col-form-label']) !!}
                <div class="col-9">
                  {!! Form::password('password', ['class' => 'form-control', 'required' => 'required']) !!}
                </div>
              </div>
            </div>
            <div class="card-footer">
              <input type="submit" value="Login" class="btn btn-primary">
              @if (Route::has('auth.register'))
                <a href="{!! route('auth.register') !!}" class="btn btn-link">
                  Register
                </a>
              @endif
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
