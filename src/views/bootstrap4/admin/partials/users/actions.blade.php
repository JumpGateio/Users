@php
  $text = [
    'actions' => 'Actions',
    'edit' => 'Edit',
    'delete' => 'Delete'
  ];

  if (isset($iconOnly) && $iconOnly === true) {
    $text = [
      'actions' => '<i class="fa fa-cog"></i>',
      'edit' => '<i class="fa fa-pencil"></i>',
      'delete' => '<i class="fa fa-trash"></i>'
    ];
  }
@endphp

<div class="btn-toolbar float-right" role="toolbar">
  @if ($user->trashed())
    <a href="{{ route('admin.users.confirm', [$user->id, 'delete', 0]) }}" class="btn btn-sm btn-outline-danger">
      Restore
    </a>
  @else
    <div class="btn-group mr-2">
      <button type="button" class="btn btn-sm btn-outline-dark dropdown-toggle"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {!! $text['actions'] !!}
      </button>
      <div class="dropdown-menu dropdown-menu-right">
        @foreach ($user->adminActions as $action)
          <a href="{{ $action->route }}" class="dropdown-item p-2">
            <i class="fa {{ $action->icon }} text-500"></i>&nbsp;&nbsp;{{ $action->text }}
          </a>
        @endforeach
      </div>
    </div>
    <div class="btn-group">
      <a href="{{ route('admin.users.edit', [$user->id]) }}" class="btn btn-sm btn-outline-purple">
        {!! $text['edit'] !!}
      </a>
      <a href="{{ route('admin.users.confirm', [$user->id, 'delete', 1]) }}" class="btn btn-sm btn-outline-danger">
        {!! $text['delete'] !!}
      </a>
    </div>
  @endif
</div>
