@extends($layout)

@section('content')
  <div class="container">
    <div class="row">
      <div class="offset-2 col-8">
        <div class="card">
          <div class="card-header">
            <strong>Reset Password</strong>
          </div>
          <div class="card-body">
            {!! Form::open(['class' => 'form-horizontal']) !!}
            {!! Form::hidden('token', $tokenString) !!}

            <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-3 col-form-label">E-Mail Address</label>

              <div class="col-9">
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>

                @if ($errors->has('email'))
                  <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class="form-group row{{ $errors->has('password') ? ' has-error' : '' }}">
              <label for="password" class="col-3 col-form-label">Password</label>

              <div class="col-9">
                <input id="password" type="password" class="form-control" name="password" required>

                @if ($errors->has('password'))
                  <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class="form-group row{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
              <label for="password-confirm" class="col-3 col-form-label">Confirm Password</label>
              <div class="col-9">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                @if ($errors->has('password_confirmation'))
                  <span class="help-block">
                      <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              Reset Password
            </button>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
