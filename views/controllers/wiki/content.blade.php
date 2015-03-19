<ol class="breadcrumb">
    <li class="{{ empty($breadcrumbs) ? 'active' : '' }}">
        <a class="ajax" href="{{ url(Config::get('lecter.uri')) }}">Home</a>
    </li>
    @foreach($breadcrumbs as $breadcrumb)
        <li class="{{ $breadcrumb['active'] == true ? 'active' : '' }}">
            @if($breadcrumb['active'] === true)
                {{ $breadcrumb['name'] }}
            @else
                <a class="ajax" href="{{ url($breadcrumb['link']) }}">{{ $breadcrumb['name'] }}</a>
            @endif
        </li>
    @endforeach
</ol>

@if($content != null && Config::get('lecter.private') == true)
    <div class="panel panel-default">
        <div class="panel-body">
            <button type="button" class="btn btn-info" id="edit">Edit</button>
            <button type="button" class="btn btn-danger" id="delete">Delete</button>
            <button type="button" class="btn btn-default" id="cancel">Cancel</button>
            <button type="button" class="btn btn-primary" id="save">Save</button>
        </div>
    </div>

    <div id="editor-container"></div>
@endif

@if($content === '' && !empty($files))
    <div class="panel panel-default">
        <ul class="list-group">
            @foreach($files as $file)
                <li class="list-group-item">
                    <i class="glyphicon glyphicon-file"></i>
                    <a class="ajax" href="{{ url($file['link']) }}">{{ $file['name'] }}</a>
                </li>
            @endforeach

            @foreach($directories as $directory)
                <li class="list-group-item">
                    <i class="glyphicon glyphicon-folder-open"></i>
                    <a class="ajax" href="{{ url($directory['link']) }}">{{ $directory['name'] }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div id="content">
    {!! $content !!}
</div>
