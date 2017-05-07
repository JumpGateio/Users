@extends($layout)

@section('content')
  <div class="uk-grid-small uk-flex-center" uk-grid>
    <div class="uk-width-1-3">
      {!! Form::open(['class' => 'uk-form-horizontal']) !!}
      <div class="uk-card uk-card-default uk-padding-remove">
        <div class="uk-card-header uk-background-gray-lighter">Register</div>
        <div class="uk-card-body">

          <div class="uk-margin">
            {!! Form::label('email', 'Email', ['class' => 'uk-form-label']) !!}
            <div class="uk-form-controls">
              {!! Form::text('email', null, ['class' => 'uk-input', 'required' => 'required']) !!}
            </div>
          </div>

          <div class="uk-margin">
            {!! Form::label('display_name', 'Display Name', ['class' => 'uk-form-label']) !!}
            <div class="uk-form-controls">
              {!! Form::text('display_name', null, ['class' => 'uk-input']) !!}
            </div>
          </div>

          <div class="uk-margin">
            {!! Form::label('password', 'Password', ['class' => 'uk-form-label']) !!}
            <div class="uk-form-controls">
              {!! Form::password('password', ['class' => 'uk-input', 'required' => 'required']) !!}
            </div>
          </div>

          <div class="uk-margin">
            {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'uk-form-label']) !!}
            <div class="uk-form-controls">
              {!! Form::password('password_confirmation', ['class' => 'uk-input', 'required' => 'required']) !!}
            </div>
          </div>

        </div>
        <div class="uk-card-footer">
          <input type="submit" value="Register" class="uk-button uk-button-primary-light uk-text-white">
          <a href="{!! route('auth.login') !!}" class="button">
            Login
          </a>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
@endsection
