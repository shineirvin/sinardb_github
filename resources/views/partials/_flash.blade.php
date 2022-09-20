@if(Session::has('flash_message'))
    <script>
        swal({
            title: "{!! session('flash_message.title') !!}",
            text: "{!! session('flash_message.message') == "Internal Server Error (Unknown)" ? 'Something went wrong!' :  session('flash_message.message') !!}",
            type: "{!! session('flash_message.level') !!}",
            @if(session('flash_message.timer'))
                timer: "100000"
            @endif
        });
    </script>
@endif