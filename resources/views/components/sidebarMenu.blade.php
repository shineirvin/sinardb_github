<li>
    <a><i class="{{ $icon }}"></i> {{ $title }} <span class="fa fa-chevron-down"></span></a>
    <ul class="nav child_menu">
        @foreach($menuList as $value)
            <li><a href="{{ $value['url'] }}">{{ $value['childTitle'] }}</a></li>
        @endforeach
    </ul>
</li>