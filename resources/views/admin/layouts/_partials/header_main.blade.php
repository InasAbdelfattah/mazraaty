<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-main">
        <div class="container">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="{{ route('admin.home') }}" class="logo">
                    <span>طلت<span>ك</span></span>
                </a>
            </div>
            <!-- End Logo container-->


            <div class="menu-extras">

                <ul class="nav navbar-nav navbar-right pull-right">
                    {{--<li>--}}
                    {{--<form role="search" class="navbar-left app-search pull-left hidden-xs">--}}
                    {{--<input type="text" placeholder="Search..." class="form-control filteriTems">--}}
                    {{--<a href=""><i class="fa fa-search"></i></a>--}}
                    {{--</form>--}}
                    {{--</li>--}}
                    <li>
                        <!-- Notification -->
                        <div class="notification-box">
                            <ul class="list-inline m-b-0">
                                <li>
                                    <a href="javascript:void(0);" class="right-bar-toggle">
                                        <i class="zmdi zmdi-notifications-none"></i>
                                    </a>
                                    <div class="noti-dot">
                                        <span class="dot"></span>
                                        <span class="pulse"></span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- End Notification bar -->
                    </li>

                    <li class="dropdown user-box">
                        <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown"
                           aria-expanded="true">
                            <img src="{{ request()->root() }}/assets/admin/images/users/avatar-1.jpg" alt="user-img"
                                 class="img-circle user-img">
                            <div class="user-status away"><i class="zmdi zmdi-dot-circle"></i></div>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="{{ route('users.edit',auth()->user()->id) }}"><i class="ti-user m-r-5"></i> الملف الشخصى</a></li>
                            <li><a href="{{ route('users.edit',auth()->user()->id) }}"><i class="ti-settings m-r-5"></i> تعديل بياناتى</a></li>
                            <!-- <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> غلق التطبيق </a></li> -->


                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti-power-off m-r-5"></i> تسجيل خروج
                                </a>
                            </li>


                        </ul>
                    </li>
                </ul>
                <form id="logout-form" action="{{ route('administrator.logout') }}" method="POST"
                      style="display: none;">
                    {{ csrf_field() }}
                </form>


                <div class="menu-item">
                    <!-- Mobile menu toggle-->
                    <a class="navbar-toggle">
                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <!-- End mobile menu toggle-->
                </div>
            </div>

        </div>
    </div>

    <div class="navbar-custom">
        <div class="container">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu" style="font-size: 14px;">


                    <li>
                        <a href="{{ route('admin.home') }}"><i class="zmdi zmdi-view-dashboard"></i>
                            <span> الرئيسية </span> </a>
                    </li>

                    @can('users_manage')
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-invert-colors"></i> <span> مستخدمى التطبيق </span> </a>
                            <ul class="submenu megamenu">

                                <li>
                                    <ul>
                                        <strong><h5 style="font-weight: 600;">إدارة التطبيق</h5></strong>
                                        <li><a href="{{ route('users.index') }}">مشاهدة إدارة التطبيق</a></li>
                                        <li><a href="{{ route('users.create') }}">إضافة مدير</a></li>
                                    </ul>
                                </li>

                                <li>
                                    <ul>
                                        <strong><h5 style="font-weight: 600;">المسجلين بالتطبيق</h5></strong>
                                        <li><a href="{{ route('users.providers_requests')}}">طلبات مزودى الخدمات</a></li>
                                        <li><a href="{{ route('users.app_providers')}}">مشاهدة مزودى الخدمة</a></li>
                                        <li><a href="{{ route('users.app_users')}}">مشاهدة المستخدمين</a></li>
                                    </ul>
                                </li>

                                @can('userDiscount_manage')
                                <li>
                                    <ul>
                                        <strong><h5 style="font-weight: 600;">الخصومات</h5></strong>
                                        <li><a href="{{ route('user_discounts.index') }}">مشاهدة الخصومات</a></li>
                                        <li><a href="{{ route('user_discounts.all') }}">تقارير الخصومات </a></li>
                                    </ul>
                                </li>
                                @endcan


                                @can('roles_manage')
                                    <li>
                                        <ul>
                                            <strong><h5 style="font-weight: 600;">الادوار والصلاحيات</h5></strong>
                                            <li><a href="{{ route('roles.index') }}">مشاهدة الادوار</a></li>
                                            <li><a href="{{ route('roles.create') }}">إضافة دور</a></li>
                                            <!-- <li><a href="{{ route('abilities.index') }}">مشاهدة الصلاحيات</a></li> -->
                                            <!-- <li><a href="ui-dripicons.html">مشاهدة الادوار</a></li>
                                            <li><a href="ui-modals.html">إضافة دور</a></li>
                                            <li><a href="ui-modals.html">مشاهدة الصلاحيات</a></li> -->
                                        </ul>
                                    </li>
                                @endcan


                            </ul>
                        </li>
                    @endcan

                    @can('companies_manage')
                        <li class="has-submenu">
                            <a href="{{ route('companies.index') }}"><i class="zmdi zmdi-view-list"></i>
                                <span>  المراكز </span>
                            </a>
                            <ul class="submenu">
                                <li style="padding:  0 25px"><a href="{{ route('companies.index') }}">مشاهدة
                                        المراكز</a></li>
                                <li style="padding:  0 25px"><a href="{{ route('companies.orders') }}"> طلبات تسجيل
                                        المراكز </a></li>
                            </ul>
                        </li>
                    @endcan

                    @can('orders_manage')
                        <li class="has-submenu">
                            <a href="{{ route('orders.index') }}"><i class="zmdi zmdi-view-list"></i>
                                <span>  الحجوزات </span>
                            </a>
                            <ul class="submenu">
                                <li style="padding:  0 25px"><a href="{{ route('orders.index') }}">عرض
                                        الحجوزات</a></li>
                                <li style="padding:  0 25px"><a href="{{ route('orders.financial_reports') }}">التقارير المالية</a></li>
                                <li style="padding:  0 25px"><a href="{{ route('orders.financial_accounts') }}">الحسابات المالية</a></li>
                                
                            </ul>
                        </li>
                    @endcan

                    <!-- @can('userDiscount_manage')
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-view-dashboard"></i>
                                <span> الخصومات </span> </a>
                            <ul class="submenu">

                                <li style="padding:  0 25px"><a href="{{ route('user_discounts.index') }}">الخصومات </a></li>

                                <li style="padding:  0 25px"><a href="{{ route('user_discounts.all') }}">تقارير الخصومات </a></li>

                            </ul>
                        </li>     
                    @endcan -->

                    @can('categories_manage')
                        <li class="has-submenu">
                            <a href="{{ route('categories.index') }}"><i class="zmdi zmdi-view-list"></i> <span> أنواع الخدمات </span>
                            </a>
                            <ul class="submenu">
                                <li style="padding:  0 25px"><a href="{{ route('categories.index') }}">مشاهدة أنواع
                                        الخدمات</a></li>
                                <li style="padding:  0 25px"><a href="{{ route('categories.create') }}"> إضافة نوع </a>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('setting_manage')
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-settings"></i><span>  الضبط </span> </a>
                            <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                        <!-- <li><a href="{{ route('administrator.settings.comments') }}">إدارة ضبط
                                                المراكز</a>
                                        </li> -->
                                        <li><a href="{{ route('settings.terms') }}">بنود الإستخدام</a></li>
                                        <li><a href="{{ route('settings.aboutus') }}">عن التطبيق</a></li>
                                        <li><a href="{{ route('settings.commission') }}">نسبة التطبيق</a></li>
                                        <li><a href="{{ route('settings.socials') }}">روابط وسائل التواصل</a></li>
                                        <li><a href="{{ route('cities.index') }}">المدن</a></li>
                                         <li><a href="{{ route('districts.index') }}">الأحياء</a></li> 
                                        <li><a href="{{ route('new-notif') }}">ارسال اشعار</a></li>
                                
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    @endcan

                    @can('support_manage')
                        <li>
                            <a href="{{ route('support.index') }}"><i class="zmdi zmdi-email-open zmdi-hc-fw"></i>
                                <span> اتصل بنا </span> </a>
                        </li>
                    @endcan

                    @can('abuses_manage')
                        <li>
                            <a href="{{ route('abuses.index') }}"><i class="zmdi zmdi-email-open zmdi-hc-fw"></i>
                                <span>بلاغات الإساءة</span> </a>
                        </li>
                    @endcan

                </ul>
                <!-- End navigation menu  -->
            </div>
        </div>
    </div>
</header>
<!-- End Navigation Bar-->


<div class="wrapper">
    <div class="container">