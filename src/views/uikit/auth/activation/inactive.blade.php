@extends($layout)

@section('content')
  <div class="uk-grid-small uk-flex-center" uk-grid>
    <div class="uk-width-1-3">
      <div class="uk-card uk-card-default">
        <div class="uk-card-header">
          <strong>
            <i class="fa fa-fw fa-exclamation-triangle uk-text-danger"></i>
            Your account is not activated
          </strong>
        </div>
        @if (! is_null($token))
          <div class="uk-card-body">
            <a href="{{ route('auth.activation.resend', $token->token) }}"
               class="uk-button uk-button-small uk-button-primary-light uk-text-white">
              Re-Send Activation Email
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
