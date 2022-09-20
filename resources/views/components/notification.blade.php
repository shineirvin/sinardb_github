<li role="presentation" class="dropdown">
    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-envelope-o"></i>
        <span class="badge bg-green">6</span>
    </a>
    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
        @component('../../components/notification/notificationList')
            @slot('formArray', [
                [
                    "title"   => "John Smith",
                    "time"    => "3 mins ago",
                    "message" => "Hello",
                ],
                [
                    "title"   => "John Wick",
                    "time"    => "3 mins ago",
                    "message" => "Film festivals used to be do-or-die moments for movie makers. They were where...",
                ],
                [
                    "title"   => "John Doe",
                    "time"    => "4 mins ago",
                    "message" => "Film festivals used to be do-or-die moments for movie makers. They were where...",
                ],
                [
                    "title"   => "John Snow",
                    "time"    => "5 mins ago",
                    "message" => "Film festivals used to be do-or-die moments for movie makers. They were where...",
                ],
            ])
        @endcomponent

        <li>
            <div class="text-center">
                <a>
                    <strong>See All Alerts</strong>
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </li>
    </ul>
</li>