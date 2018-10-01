<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-main">
        <div class="container">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="<?php echo e(route('admin.home')); ?>" class="logo">
                    <!-- <span>مز<span> رعتى</span></span> -->
                    <span><?php echo e(config('app.name')); ?></span>
                </a>
            </div>
            <!-- End Logo container-->


            <div class="menu-extras">

                <ul class="nav navbar-nav navbar-right pull-right">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notifs_manage')): ?>
                    <li>
                  
                        <div class="notification-box">
                            <ul class="list-inline m-b-0">
                                <li>
                                    <a href="<?php echo e(route('notifs')); ?>" class="right-bar-toggle">
                                        <i class="zmdi zmdi-notifications-none"></i>
                                    </a>
                                    <!-- <div class="noti-dot">
                                        <span class="dot"></span>
                                        <span class="pulse"></span>
                                    </div> -->
                                </li>
                            </ul>
                        </div>
                       
                    </li>
                    <?php endif; ?>

                    <li class="dropdown user-box">
                        <a href="" class="dropdown-toggle waves-effect waves-light profile " data-toggle="dropdown"
                           aria-expanded="true">
                            <img src="<?php echo e(url('files/users/' . auth()->user()->image)); ?>" alt="user-img"
                                 class="img-circle user-img">
                            <div class="user-status away"><i class="zmdi zmdi-dot-circle"></i></div>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="<?php echo e(route('users.profile',['id'=>auth()->user()->id])); ?>"><i class="ti-user m-r-5"></i> الملف الشخصى</a></li>
                            <li><a href="<?php echo e(route('users.editProfile',['id'=>auth()->user()->id])); ?>"><i class="ti-settings m-r-5"></i> تعديل بياناتى</a></li>
                            <!-- <li><a href="javascript:void(0)"><i class="ti-lock m-r-5"></i> غلق التطبيق </a></li> -->

                            <li>
                                <a href="<?php echo e(route('logout')); ?>"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti-power-off m-r-5"></i> تسجيل خروج
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <form id="logout-form" action="<?php echo e(route('administrator.logout')); ?>" method="POST"
                      style="display: none;">
                    <?php echo e(csrf_field()); ?>

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
                        <a href="<?php echo e(route('admin.home')); ?>"><i class="zmdi zmdi-view-dashboard"></i>
                            <span> الرئيسية </span> </a>
                    </li>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users_manage')): ?>
                        <li>
                            <a href="<?php echo e(route('users.app_users')); ?>"><i class="zmdi zmdi-invert-colors"></i> <span>إدارة العملاء</span> </a>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users_manage')): ?>
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-invert-colors"></i> <span>مستخدمى لوحة التحكم </span> </a>

                            <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                    
                                        <li><a href="<?php echo e(route('users.index')); ?>">مديرى التطبيق المفعلين</a></li>
                                        <li><a href="<?php echo e(route('users.suspended_admins')); ?>">مديرى التطبيق المعطلين</a></li>
                                        <li><a href="<?php echo e(route('roles.index')); ?>">الأدوار والصلاحيات</a></li>
                                                                        
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting_manage')): ?>
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-settings"></i><span>  الإعدادات </span> </a>
                            <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                        <li><a href="<?php echo e(route('settings.generalSettings')); ?>">اعدادات النظام</a></li>
                                        <li><a href="<?php echo e(route('cities.index')); ?>">المدن</a></li>
                                        <li><a href="<?php echo e(route('measurementUnits.index')); ?>">وحدات القياس</a></li>
                                        <li><a href="<?php echo e(route('categories.index')); ?>">الأقسام الرئيسية</a></li>
                                        <li><a href="<?php echo e(route('subcategories')); ?>">الأقسام الفرعية</a></li>
                                        <li><a href="<?php echo e(route('products.index')); ?>">المنتجات</a></li>
                                        <li><a href="<?php echo e(route('offers.index')); ?>">العروض</a></li>
                                       
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('content_manage')): ?>
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-settings"></i><span>محتوى التطبيق </span> </a>
                            <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                        
                                        <li><a href="<?php echo e(route('settings.terms')); ?>">الشروط والأحكام</a></li>
                                        <li><a href="<?php echo e(route('settings.aboutus')); ?>">عن التطبيق</a></li>
                                        <li><a href="<?php echo e(route('settings.contacts')); ?>">اتصل بنا</a></li>
                                        
                                        <!-- <li>
                                            <a href="<?php echo e(route('support.index')); ?>">رسائل تواصل معنا</a>
                                        </li> -->
                                        <li>
                                            <a href="<?php echo e(route('faqs.index')); ?>">الأسئلة المتكررة</a>
                                        </li>
                                        <li><a href="<?php echo e(route('settings.socials')); ?>">روابط التواصل</a></li> 
                                        
                                        
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ads_manage')): ?>
                        <li><a href="<?php echo e(route('ads.index')); ?>">الإعلانات</a></li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('settings_manage')): ?>
                        <li><a href="<?php echo e(route('cities.city_votes')); ?>">التصويت على المدن</a></li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('orders_manage')): ?>
                        
                        <li class="has-submenu">
                            <a href="<?php echo e(route('orders.index').'?order_type=orders&status=""&from=""&to=""'); ?>"><i class="zmdi zmdi-invert-colors"></i> <span>الطلبات</span> </a>
                            <!-- <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                        <li><a href="<?php echo e(route('orders.index').'?status=""&from=""&to=""'); ?>">طلبات جديدة لمزرعتى</a></li>
                                    </ul>
                                </li>
                            </ul> -->
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('coupons_manage')): ?>
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-invert-colors"></i> <span>الرموز الترويجية</span> </a>
                            <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                        <li><a href="<?php echo e(route('discounts.index')); ?>">أكواد الخصم</a></li>
                                        <li><a href="<?php echo e(route('userCoupons')); ?>">المستفيدين من الخصم</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>


                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('users_manage')): ?>
                        <li class="has-submenu">
                            <a href="#"><i class="zmdi zmdi-invert-colors"></i> <span>التقارير</span> </a>
                            <ul class="submenu megamenu">
                                <li>
                                    <ul>
                                        <!-- <li><a href="#">المنتجات الأكثر مبيعا</a></li> -->
                                        <li><a href="<?php echo e(route('orders.index').'?order_type=reports&status=""&from=""&to=""'); ?>">طلبات مزرعتى</a></li>
                                        <!-- <li><a href="#">تقارير أكواد الخصم</a></li> -->
                                
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <!-- <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notifs_manage')): ?>
                        <li><a href="<?php echo e(route('notifs')); ?>"><i class="zmdi zmdi-invert-colors"></i> الإشعارات</a></li>
                    <?php endif; ?> -->

                </ul>
                <!-- End navigation menu  -->
            </div>
        </div>
    </div>
</header>
<!-- End Navigation Bar-->


<div class="wrapper">
    <div class="container">