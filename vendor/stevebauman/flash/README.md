# Flash

## Installation

> **Note**: This installation assumes you've already included [Sweet Alert](http://t4t5.github.io/sweetalert/)
> in your html layout.

Require `flash` in your `composer.json` file:

```json
"stevebauman/flash": "1.0.*",
```

Then run `composer update`.

Now create a partial blade file (maybe `resources/views/_flash.blade.php`?), and insert the following:

```html
@if(session()->has('flash_message'))
    <script type="text/javascript">
        swal({
            title: "{!! session('flash_message.title') !!}",
            text: "{!! session('flash_message.message') !!}",
            type: "{!! session('flash_message.level') !!}",
            @if(session('flash_message.timer')) timer: "{!! session('flash_message.timer') !!}" @endif
        });
    </script>
@endif
```

Your all set!

## Usage

Call the `flash()` helper method like so:

```php
flash()->create($title = 'Custom', $message = 'Message Content', $level = 'info');

flash()->success('Success!', "You've successfully done something, congrats!");

flash()->info('Info!', "Just letting you know something informative.");

flash()->warning('Warning!', 'Hey watch out, somethings going on!');

flash()->error('Error!', "Uh oh, there was an error doing something!");
```

Setting an automatic timeout of the notification:

> **Note**, by default notifications have a 2 second timeout.

```php
flash()->setTimer(5000)->success('Success!', 'This notification will disappear in five seconds.');
```
