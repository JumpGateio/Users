@extends($layout)

@section('content')
  <div class="uk-grid-small uk-flex-center" uk-grid>
    <div class="uk-width-1-3">
      {!! Form::open(['class' => 'uk-form-horizontal']) !!}
      {!! Form::hidden('token', $tokenString) !!}
      <div class="uk-card uk-card-default">
        <div class="uk-card-header uk-background-primary-light uk-text-white">
          <strong>Reset Password</strong>
        </div>
        <div class="uk-card-body">

          <div class="uk-margin">
            <label for="email" class="uk-form-label{{ $errors->has('email') ? ' uk-text-danger' : '' }}">Email</label>
            <div class="uk-form-controls">
              <input id="email" type="email" class="uk-input{{ $errors->has('email') ? ' uk-form-danger' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
              @if ($errors->has('email'))
                <span class="uk-text-small uk-text-muted">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="uk-margin">
            <label for="password" class="uk-form-label{{ $errors->has('password') ? ' uk-text-danger' : '' }}">Password</label>
            <div class="uk-form-controls">
              <input id="password" type="password" class="uk-input{{ $errors->has('password') ? ' uk-form-danger' : '' }}" name="password" value="{{ old('password') }}" required autofocus>
              @if ($errors->has('password'))
                <span class="uk-text-small uk-text-muted">
                  <strong>{{ $errors->first('password') }}</strong>
                </span>
              @endif
            </div>
          </div>

          <div class="uk-margin">
            <label for="password-confirm" class="uk-form-label{{ $errors->has('password_confirmation') ? ' uk-text-danger' : '' }}">Confirm Password</label>
            <div class="uk-form-controls">
              <input id="password-confirm" type="password" class="uk-input{{ $errors->has('password_confirmation') ? ' uk-form-danger' : '' }}" name="password_confirmation" value="{{ old('email') }}" required autofocus>
              @if ($errors->has('password_confirmation'))
                <span class="uk-text-small uk-text-muted">
                  <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
              @endif
            </div>
          </div>
        </div>
        <div class="uk-card-footer">
          <button type="submit" class="uk-button uk-button-small uk-button-primary-light uk-text-white">
            Reset Password
          </button>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
@endsection
