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
            <div class="form-group row{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-3 col-form-label">E-Mail Address</label>

              <div class="col-9">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                  <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-primary">
              Send Password Reset Link
            </button>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
@endsection
