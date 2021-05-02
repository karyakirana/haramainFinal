@props(['title'=>'', 'toolbar'=>''])
<div class="card card-custom">
    @if($title != '')
    <div class="card-header">
        <div class="card-title">
            {{ $title }}
        </div>
        <div class="card-toolbar">
            {{ $toolbar }}
        </div>
    </div>
    @endif
    <div class="card-body">
       {{ $slot }}
    </div>
</div>
