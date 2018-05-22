@extends($layout)

@section('content')
  <div class="uk-grid-small uk-flex-center" uk-grid>
    <div class="uk-width-1-3">
      <div class="uk-card uk-card-default">
        <div class="uk-card-header uk-background-gray-lighter">
          <strong>
            <i class="fa fa-fw fa-info-circle uk-text-info"></i>
            Have them verify their email
          </strong>
        </div>
        <div class="uk-card-body">
          An email has been sent to the email you specified.  Please ask them to follow the link in that email to
          activate their account.
        </div>
      </div>
    </div>
  </div>
@endsection
