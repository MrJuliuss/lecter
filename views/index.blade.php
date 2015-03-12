@extends('lecter::layouts.master')

@section('content')
<nav class="navbar navbar-default">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url(Config::get('lecter.uri'), [], null) }}">{{ Config::get('lecter.app_name') }}</a>
    </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav multi-level">
                @include('lecter::partials.nav', ['navBar' => $navBar, 'root' => $root])
            </ul>
        </div>
</nav>

<div class="container">
    <div class="content">
        <ol class="breadcrumb">
            <li class="{{ empty($breadcrumbs) ? 'active' : '' }}">
                <a href="{{ url(Config::get('lecter.uri')) }}">Home</a>
            </li>
            @foreach($breadcrumbs as $breadcrumb)
                <li class="{{ $breadcrumb['active'] == true ? 'active' : '' }}">
                    @if($breadcrumb['active'] === true)
                        {{ $breadcrumb['name'] }}
                    @else
                        <a href="{{ url($breadcrumb['link']) }}">{{ $breadcrumb['name'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>

        @if($content === '' && !empty($files))
            <div class="panel panel-default">
                <ul class="list-group">
                    @foreach($files as $file)
                        <li class="list-group-item">
                            <i class="glyphicon glyphicon-file"></i>
                            <a href="{{ url($file['link']) }}">{{ $file['name'] }}</a>
                        </li>
                    @endforeach

                    @foreach($directories as $directory)
                        <li class="list-group-item">
                            <i class="glyphicon glyphicon-folder-open"></i>
                            <a href="{{ url($directory['link']) }}">{{ $directory['name'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {!! $content !!}
    </div>
</div>
@stop
