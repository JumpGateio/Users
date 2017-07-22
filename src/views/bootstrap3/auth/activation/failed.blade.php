@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading background-gray-lighter">
            <strong>Failed to activate your account</strong>
          </div>
          <div class="panel-body">
            @if (! is_null($token) && $token->isExpired())
              Your token has expired.  Please click the relevant link below to generate a new one.
              <br />
              <br />
              <div class="btn-toolbar">
                <div class="btn-group mr-2">
                  <a href="{{ route('auth.password.reset') }}" class="btn btn-sm btn-primary">Forgot Password</a>
                </div>
                <div class="btn-group">
                  <a href="{{ route('auth.activation.resend', $token->token) }}" class="btn btn-sm btn-primary">
                    Re-Send Activation Email
                  </a>
                </div>
              </div>
            @else
              Please check that you have the URL correct (same as the one in your email).
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
