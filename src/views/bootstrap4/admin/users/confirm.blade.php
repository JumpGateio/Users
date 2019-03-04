@section('title')
  Confirm Action
@endsection

<div class="row row-inside background-red text-white p-3 text-center font-weight-bold">
  WARNING! You are about to perform a dangerous action.
</div>
{!! Form::open() !!}
<div class="row row-inside background-600 text-white p-5 pl-3 mb-3">
  <div class="lead">{{ $message }}</div>
</div>
<a href="{{ route('admin.users.index') }}" class="btn btn-danger w-25">
  Cancel
</a>
<input type="submit" value="Continue" class="btn btn-primary w-25">
{!! Form::close() !!}
