@foreach($navBar as $key => $tree)
    @if(is_array($tree))
    <li class="dropdown">
        <a href="{{ $root }}/{{ $key }}" class="dropdown-toggle dropdown-item" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ $key }}
            <span class="caret"></span>
        </a>
        @include('lecter::partials.dropdown', ['tree' => $tree, 'root' => $root .'/'.$key ])
    </li>
    @endif
@endforeach
