@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-offset-3 col-lg-6">
        <div class="panel panel-default">
          <div class="panel-heading background-gray-lighter">
            <strong>Your account is not activated</strong>
          </div>
          <div class="panel-body">
            @if (! is_null($token))
              <a href="{{ route('auth.activation.resend', $token->token) }}"
                 class="btn btn-sm btn-primary">
                Re-Send Activation Email
              </a>
            @else
              <a href="{{ route('auth.login') }}"
                 class="btn btn-sm btn-primary">
                Return to login
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
