@extends($layout)

@section('content')
  <div class="uk-grid-small uk-flex-center" uk-grid>
    <div class="uk-width-1-3">
      <div class="uk-card uk-card-default">
        <div class="uk-card-header">
          <strong>
            <i class="fa fa-fw fa-info-circle uk-text-info"></i>
            Please verify your email
          </strong>
        </div>
        <div class="uk-card-body">
          An email has been sent to your account.  Please follow the link in that email to activate your account.
        </div>
      </div>
    </div>
  </div>
@endsection
