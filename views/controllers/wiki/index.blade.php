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
        <a class="ajax navbar-brand" href="{{ url(Config::get('lecter.uri')) }}">{{ Config::get('lecter.app_name') }}</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul id="navigation" class="nav navbar-nav multi-level">
            @include('lecter::partials.nav', ['navBar' => $navBar, 'root' => $root])
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li>
                <img class="ajax-loader" src="img/mrjuliuss/lecter/ajax-load.gif" />
            </li>
            @if(Auth::check())
            <li>
                <a title="Logout" href="{{ url('auth/logout') }}">
                    <i class="glyphicon glyphicon-share-alt"></i>
                    <span class="text">Logout</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</nav>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/showdown/0.4.0/Showdown.min.js"></script>
<script type="text/jsx" src="/jsx/mrjuliuss/lecter/editor.jsx"></script>

<div class="container">
    <div class="content">
        @include('lecter::controllers.wiki.content', ['breadcrumbs' => $breadcrumbs, 'content' => $content, 'files' => $files, 'directories' => $directories])
    </div>
</div>
@stop
