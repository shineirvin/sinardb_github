<div class="col-md-3 left_col menu_fixed">
    <div class="left_col scroll-view">

        <div class="navbar nav_title" style="border: 0;">
            <a href="" class="site_title"><i class="fa fa-shopping-cart"></i> <span>
                {!! App\Models\Company::find(1) ? App\Models\Company::find(1)->name : "Sinar Printing" !!}
            </span></a>
        </div>

        <div class="clearfix"></div>

        <br>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li class="{!! \Request::is('company/*') ? 'active' : '' !!}">
                        <a href="{!! url('company') !!}"><i class="fa fa-shopping-cart"></i> Toko </a>
                    </li>
                    <li class="{!! \Request::is('customers/*') ? 'active' : '' !!}">
                        <a href="{!! url('customers') !!}"><i class="fa fa-users"></i> Customers </a>
                    </li>
                    <li class="{!! \Request::is('items/*') ? 'active' : '' !!}">
                        <a href="{!! url('items') !!}"><i class="fa fa-cog"></i> Items </a>
                    </li>
                    <li class="{!! \Request::is('po/*') ? 'active' : '' !!}">
                        <a href="{!! url('po') !!}"><i class="fa fa-list"></i> Transaksi </a>
                    </li>

                    {{-- @component('../components/sidebarMenu')
                        @slot('icon', 'fa fa-users')
                        @slot('title', 'Account')
                        @slot('menuList', [
                            [
                                "childTitle" => "User Profile",
                                "url" => url('profile')
                            ],
                            [
                                "childTitle" => "Change Password",
                                "url" => url('changepassword')
                            ],
                            [
                                "childTitle" => "IP Whitelist",
                                "url" => url('ipwhitelist')
                            ],
                        ])
                    @endcomponent

                    @component('../components/sidebarMenu')
                        @slot('icon', 'fa fa-cog')
                        @slot('title', 'Config')
                        @slot('menuList', [
                            [
                                "childTitle" => "Configuration",
                                "url" => url('configuration')
                            ],
                            [
                                "childTitle" => "General Settings",
                                "url" => url('general')
                            ],
                            [
                                "childTitle" => "Access Keys",
                                "url" => url('accesskeys')
                            ],
                        ])
                    @endcomponent --}}
              </ul>
            </div>

        </div>
        <!-- /sidebar menu -->
    </div>
</div>