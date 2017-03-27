@extends($layout)

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="offset-lg-3 col-lg-6">
        <div class="panel panel-default">
          <div class="card">
            <div class="card-header">
              <strong>Your account is not activated</strong>
            </div>
            <div class="card-block">
              @if (! is_null($token))
                <a href="{{ route('auth.activation.resend', $token->token) }}"
                   class="btn btn-sm btn-primary">
                  Re-Send Activation Email
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
