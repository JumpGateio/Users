@extends($layout)

@section('content')
  <div class="uk-grid-small uk-flex-center" uk-grid>
    <div class="uk-width-1-3">
      <div class="uk-card uk-card-default">
        <div class="uk-card-header">
          <strong>
            <i class="fa fa-fw fa-exclamation-triangle uk-text-danger"></i>
            Failed to activate your account
          </strong>
        </div>
        <div class="uk-card-body">
          @if (! is_null($token) && $token->isExpired())
            Your token has expired.  Please click the relevant link below to generate a new one.
            <br />
            <br />
            <div class="uk-column-1-2">
              <p><a href="{{ route('auth.password.reset') }}" class="uk-button uk-button-small uk-button-primary-light uk-text-white">Forgot Password</a></p>
              <p><a href="{{ route('auth.activation.resend', $token->token) }}" class="uk-button uk-button-small uk-button-primary-light uk-text-white">Re-Send Activation Email</a></p>
            </div>
          @else
            Please check that you have the URL correct (same as the one in your email).
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
