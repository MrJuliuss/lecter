<ul class="dropdown-menu multi-level">
    @foreach($tree as $keyTree => $item)
        @if(!is_array($item))
            <li><a class="dropdown-item" href="{{ url($root.'/'.$item) }}">{{ explode('.', $item)[0] }}</a></li>
        @else
            <li class="dropdown-submenu dropdown-item">
                <a href="{{ url($root.'/'.$keyTree) }}" class="dropdown-toggle">{{ $keyTree }}</a>
                @include('lecter::partials.dropdown', ['tree' => $item, 'root' => $root.'/'.$keyTree])
            </li>
        @endif
    @endforeach
</ul>
