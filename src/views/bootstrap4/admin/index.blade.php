@section('title')
  Admin Dashboard
@endsection

<div class="row">
  <div class="col-md-3">
    {!! HTML::adminTile('background-success', 'text-black', 'fa fa-4x fa-users', $users .' users') !!}
  </div>
</div>
