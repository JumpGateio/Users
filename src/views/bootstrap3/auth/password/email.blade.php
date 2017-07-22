@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading background-gray-lighter">
            <strong>Reset Password</strong>
          </div>
          {!! Form::open(['class' => 'form-horizontal']) !!}
          <div class="panel-body">
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-lg-3 control-label">E-Mail Address</label>

              <div class="col-lg-9">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                  <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>
            </div>
          </div>
          <div class="panel-footer">
            <div class="row">
              <div class="col-lg-offset-3 col-lg-9">
                <button type="submit" class="btn btn-primary">
                  Send Password Reset Link
                </button>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
@endsection
