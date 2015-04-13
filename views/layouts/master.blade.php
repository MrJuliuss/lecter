<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/styles/zenburn.min.css">
        <link rel="stylesheet" type="text/css" href="{{ lecter_path() }}/css/mrjuliuss/lecter/lecter.css">
        <link rel="stylesheet" type="text/css" href="{{ lecter_path() }}/css/mrjuliuss/lecter/bootswatch.css">

        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        @if(Config::get('lecter.private') === true)
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/react/0.13.1/react.js"></script>
            @if(Config::get('app.debug') === true)
                <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/react/0.13.1/JSXTransformer.js"></script>
            @else
                <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/showdown/0.3.1/showdown.min.js"></script>
                <script type="text/javascript" src="{{ lecter_path() }}/js/mrjuliuss/lecter/editor.js"></script>
            @endif
        @endif

        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js"></script>
        <script type="text/javascript" src="{{ lecter_path() }}/js/mrjuliuss/lecter/lecter.js"></script>
        <script>hljs.initHighlightingOnLoad();</script>
    </head>

    <body>
        @yield('content')
    </body>
</html>