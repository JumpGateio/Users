@extends('backpack::layout')

@section('header')
  <section class="content-header">
    <h1>
      {{ trans('backpack::crud.preview') }} <span class="text-lowercase">{{ $crud->entity_name }}</span>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
      </li>
      <li><a href="{{ url($crud->route) }}" class="text-capitalize">{{ $crud->entity_name_plural }}</a></li>
      <li class="active">{{ trans('backpack::crud.preview') }}</li>
    </ol>
  </section>
@endsection

@section('content')
  @if ($crud->hasAccess('list'))
    <a href="{{ url($crud->route) }}"><i class="fa fa-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }}
      <span class="text-lowercase">{{ $crud->entity_name_plural }}</span></a><br><br>
  @endif

  <h1>{{ $entry->display_name }}</h1>

  <div class="row">
    <div class="col-sm-8">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">
            Details
          </h3>
        </div>
        <div class="box-body" style="padding: 0;">
          <div class="row">
            <div class="col-md-6">
              <table class="table">
                <tbody>
                  <tr>
                    <td><strong>Email:</strong></td>
                    <td>{{ $entry->email }}</td>
                  </tr>
                  <tr>
                    <td><strong>Display Name:</strong></td>
                    <td>{{ $entry->details->display_name }}</td>
                  </tr>
                  <tr>
                    <td><strong>Full Name:</strong></td>
                    <td>{{ $entry->details->full_name }}</td>
                  </tr>
                  <tr>
                    <td><strong>Status:</strong></td>
                    <td>{{ $entry->status->label }}</td>
                  </tr>
                  <tr>
                    <td><strong>Timezone:</strong></td>
                    <td>{{ $entry->details->timezone }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table">
                <tbody>
                  <tr>
                    <td><strong>Last authenticated through:</strong></td>
                    <td>{{ is_null($entry->authenticated_as) ? 'Site' : $entry->authenticated_as }}</td>
                  </tr>
                  <tr>
                    <td><strong>Last authenticated At:</strong></td>
                    <td>{{ is_null($entry->authenticated_at) ? 'Never' : $entry->authenticated_at->format(config('backpack.base.default_datetime_format')) }}</td>
                  </tr>
                  <tr>
                    <td><strong>Activated At:</strong></td>
                    <td>{{ is_null($entry->activated_at) ? 'Never' : $entry->activated_at->format(config('backpack.base.default_datetime_format')) }}</td>
                  </tr>
                  <tr>
                    <td><strong>Blocked At:</strong></td>
                    <td>{{ is_null($entry->blocked_at) ? 'Never' : $entry->blocked_at->format(config('backpack.base.default_datetime_format')) }}</td>
                  </tr>
                  <tr>
                    <td><strong>Password Updated At:</strong></td>
                    <td>{{ is_null($entry->password_updated_at) ? 'Never' : $entry->password_updated_at->format(config('backpack.base.default_datetime_format')) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div><!-- /.box -->
    @if (config('jumpgate.users.enable_social'))
      <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              Social Logins
            </h3>
          </div>
          <div class="box-body" style="padding: 0;">
            @if (count($entry->socials) > 0)
              <table class="table">
                <thead>
                  <tr>
                    <th>Provider</th>
                    <th>Email</th>
                    <th>Avatar</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($entry->socials as $social)
                    <tr>
                      <td>{{ ucwords($social->provider) }}</td>
                      <td>{{ $social->email }}</td>
                      <td><img src="{{ $social->avatar }}" style="width: 20px;" /></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
        </div>
      @endif
    </div>
    <div class="col-sm-4">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Roles</h3>
        </div>
        <div class="box-body">
          <ul class="list-inline">
            @foreach ($entry->roles as $role)
              <li>{{ $role->name }}</li>
            @endforeach
          </ul>
        </div><!-- /.box-body -->
      </div>
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Permissions</h3>
        </div>
        <div class="box-body">
          <ul class="list-inline">
            <ul class="list-inline">
              @foreach ($entry->roles->permissions as $permission)
                <li>{{ $permission->name }}</li>
              @endforeach
            </ul>
          </ul>
        </div><!-- /.box-body -->
      </div>
    </div>
  </div>

@endsection
