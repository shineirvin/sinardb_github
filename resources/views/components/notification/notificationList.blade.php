@foreach($formArray as $value)
    <li>
        <a>
            <span>
                <span>{{ $value['title'] }}</span>
                <span class="time">{{ $value['time'] }}</span>
            </span>
            <span class="message">
                {{ $value['message'] }}
            </span>
        </a>
    </li>
@endforeach