@extends($layout)

@section('content')
  <div class="uk-grid-small uk-flex-center" uk-grid>
    <div class="uk-width-1-3">
      {!! Form::open(['class' => 'uk-form-horizontal']) !!}
      <div class="uk-card uk-card-default uk-padding-remove">
        <div class="uk-card-header uk-background-gray-lighter">Login</div>
        <div class="uk-card-body">

          <div class="uk-margin">
            {!! Form::label('email', 'Email', ['class' => 'uk-form-label']) !!}
            <div class="uk-form-controls">
              {!! Form::text('email', null, ['class' => 'uk-input', 'required' => 'required']) !!}
            </div>
          </div>

          <div class="uk-margin">
            {!! Form::label('password', 'Password', ['class' => 'uk-form-label']) !!}
            <div class="uk-form-controls">
              {!! Form::password('password', ['class' => 'uk-input', 'required' => 'required']) !!}
            </div>
          </div>

        </div>
        <div class="uk-card-footer">
          <input type="submit" value="Login" class="uk-button uk-button-primary-light uk-text-white">
          <a href="{!! route('auth.register') !!}" class="button">
            Register
          </a>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
@endsection


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
              <a href="{!! route('auth.register') !!}" class="btn btn-link">
                Register
              </a>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
