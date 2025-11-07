@php
    \App\Core\KTBootstrap::init();

    $bootstrapperClass = config('settings.KT_THEME_BOOTSTRAP.default');

    if ($bootstrapperClass && class_exists($bootstrapperClass)) {
        app($bootstrapperClass)->init();
    }

    $sidebarMenu = [
        [
            'title' => __('Dashboard'),
            'icon' => 'element-11',
            'route' => url('/'),
            'urlPattern' => ['/', 'home'],
        ],
        [
            'title' => __('Containers'),
            'icon' => 'truck',
            'route' => Route::has('containers.index') ? route('containers.index') : '#',
            'pattern' => ['containers.*'],
        ],
        [
            'title' => __('Customers'),
            'icon' => 'profile-circle',
            'route' => Route::has('customers.index') ? route('customers.index') : '#',
            'pattern' => ['customers.*'],
        ],
        [
            'title' => __('Contracts'),
            'icon' => 'address-book',
            'pattern' => ['contracts.*'],
            'children' => [
                /*[
                    'title' => __('All Contracts'),
                    'route' => Route::has('contracts.index') ? route('contracts.index') : '#',
                    'pattern' => ['contracts.index', 'contracts.show', 'contracts.edit', 'contracts.update'],
                ],*/
                [
                    'title' => __('Business Contracts'),
                    'route' => Route::has('contracts.index.by-type') ? route('contracts.index.by-type', ['type' => 'business']) : '#',
                    'pattern' => ['contracts.index.by-type'],
                    'query' => ['type' => 'business'],
                ],
                [
                    'title' => __('Individual Contracts'),
                    'route' => Route::has('contracts.index.by-type') ? route('contracts.index.by-type', ['type' => 'individual']) : '#',
                    'pattern' => ['contracts.index.by-type'],
                    'query' => ['type' => 'individual'],
                ],
            ],
        ],
        [
            'title' => __('Payments'),
            'icon' => 'wallet',
            'route' => Route::has('payments.index') ? route('payments.index') : '#',
            'pattern' => ['payments.*'],
        ],
        [
            'title' => __('Offers'),
            'icon' => 'present',
            'route' => Route::has('offers.index') ? route('offers.index') : '#',
            'pattern' => ['offers.*', 'offers-search'],
        ],
        [
            'title' => __('Reports'),
            'icon' => 'chart-line',
            'pattern' => ['reports.*'],
            'children' => [
                [
                    'title' => __('Container Status'),
                    'route' => Route::has('reports.container-status') ? route('reports.container-status') : '#',
                    'pattern' => ['reports.container-status', 'reports.container-status.print'],
                ],
                [
                    'title' => __('Daily Report'),
                    'route' => Route::has('reports.daily-report') ? route('reports.daily-report') : '#',
                    'pattern' => ['reports.daily-report', 'reports.daily-report.print'],
                ],
                [
                    'title' => __('Monthly Report'),
                    'route' => Route::has('reports.monthly-report') ? route('reports.monthly-report') : '#',
                    'pattern' => ['reports.monthly-report'],
                ],
                [
                    'title' => __('Receipts Report'),
                    'route' => Route::has('reports.receipts-report') ? route('reports.receipts-report') : '#',
                    'pattern' => ['reports.receipts-report'],
                ],
            ],
        ],
        [
            'title' => __('Container Fills'),
            'icon' => 'cube',
            'route' => Route::has('contract-container-fills.index') ? route('contract-container-fills.index') : '#',
            'pattern' => ['contract-container-filled'],
        ],
        [
            'title' => __('Receipts'),
            'icon' => 'receipt',
            'route' => Route::has('receipts.index') ? route('receipts.index') : '#',
            'pattern' => ['receipts.*'],
        ],
        [
            'title' => __('Cars'),
            'icon' => 'car',
            'route' => Route::has('cars.index') ? route('cars.index') : '#',
            'pattern' => ['cars.*'],
        ],
        //[
        //    'title' => __('Employees'),
        //    'icon' => 'user-square',
        //    'route' => Route::has('employees.index') ? route('employees.index') : '#',
        //    'pattern' => ['employees.*'],
        //],
        [
            'title' => __('Users'),
            'icon' => 'user',
            'route' => Route::has('users.index') ? route('users.index') : '#',
            'pattern' => ['users.*'],
        ],
    ];

    $isMenuItemActive = static function (array $item) use (&$isMenuItemActive): bool {
        $patterns = (array) ($item['pattern'] ?? []);
        foreach ($patterns as $pattern) {
            if (!empty($pattern) && request()->routeIs($pattern)) {
                if (isset($item['query']) && is_array($item['query'])) {
                    $allMatch = collect($item['query'])->every(fn ($value, $key) => request()->route($key) === $value || request()->get($key) === $value);
                    if ($allMatch) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }

        $urlPatterns = (array) ($item['urlPattern'] ?? []);
        foreach ($urlPatterns as $pattern) {
            if (!empty($pattern) && request()->is($pattern)) {
                return true;
            }
        }

        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                if ($isMenuItemActive($child)) {
                    return true;
                }
            }
        }

        return false;
    };
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {!! printHtmlAttributes('html') !!} {!! printHtmlClasses('html') !!}>
<head>
    <base href="{{ url('/') }}/" />
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta charset="utf-8" />
    <meta name="description" content="@yield('meta_description', config('app.description', ''))" />
    <meta name="keywords" content="@yield('meta_keywords', '')" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
    <meta property="og:type" content="@yield('meta_og_type', 'article')" />
    <meta property="og:title" content="@yield('meta_og_title', config('app.name', 'Laravel'))" />
    <meta property="og:url" content="@yield('meta_og_url', request()->url())" />
    <meta property="og:site_name" content="@yield('meta_og_site_name', config('app.name', 'Laravel'))" />
    <link rel="canonical" href="@yield('canonical', request()->url())" />

    {!! includeFavicon() !!}

    <!--begin::Fonts-->
    {!! includeFonts() !!}
    <!--end::Fonts-->

    @stack('before-vendor-styles')

    <!--begin::Vendor Stylesheets-->
    @foreach (getVendors('css') as $path)
        <link rel="stylesheet" href="{{ asset($path) }}">
    @endforeach
    <!--end::Vendor Stylesheets-->

    <!--begin::Global Stylesheets Bundle-->
    @foreach (getGlobalAssets('css') as $path)
        <link rel="stylesheet" href="{{ asset($path) }}">
    @endforeach
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Custom Stylesheets-->
    @foreach (getCustomCss() as $path)
        <link rel="stylesheet" href="{{ asset($path) }}">
    @endforeach
    <!--end::Custom Stylesheets-->

    @stack('styles')
</head>

<body {!! printHtmlAttributes('body') !!} {!! printHtmlClasses('body') !!}>
    <script>
        var defaultThemeMode = "{{ getModeDefault() }}";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>

    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!--begin::Header-->
            <div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}" data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}" data-kt-sticky-animation="false">
                <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
                    <!--begin::Sidebar mobile toggle-->
                    <div class="d-flex align-items-center d-lg-none ms-n2 me-1" title="{{ __('Show sidebar menu') }}">
                        <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                            {!! getIcon('abstract-14', 'fs-2') !!}
                        </div>
                    </div>
                    <!--end::Sidebar mobile toggle-->

                    <!--begin::Logo-->
                    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                        <a href="{{ url('/') }}" class="d-lg-none">
                            <img alt="{{ config('app.name') }}" src="{{ asset('assets/media/logos/default-small.svg') }}" class="h-30px">
                        </a>
                    </div>
                    <!--end::Logo-->

                    <!--begin::Header wrapper-->
                    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
                        <!--begin::Menu wrapper-->
                        <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                            <div class="menu menu-rounded menu-column menu-lg-row align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
                                @yield('header-menu')
                            </div>
                        </div>
                        <!--end::Menu wrapper-->

                        <!--begin::Navbar-->
                        <div class="app-navbar flex-shrink-0">
                            <!--begin::Header menu toggle-->
                            <div class="app-navbar-item d-lg-none ms-2" title="{{ __('Show header menu') }}">
                                <div class="btn btn-flex btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_header_menu_toggle">
                                    {!! getIcon('element-4', 'fs-2') !!}
                                </div>
                            </div>
                            <!--end::Header menu toggle-->

                            <!--begin::Language menu-->
                            <div class="app-navbar-item ms-1 ms-lg-3">
                                <div class="btn btn-flex btn-icon btn-custom btn-active-light-primary w-35px h-35px" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-offset="0px, 10px">
                                    <img class="w-20px h-20px rounded" src="{{ asset(app()->getLocale() === 'ar' ? 'assets/media/flags/saudi-arabia.svg' : 'assets/media/flags/united-states.svg') }}" alt="{{ strtoupper(app()->getLocale()) }}" />
                                </div>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-150px py-4 fs-7" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="{{ route('locale.set', 'en') }}" class="menu-link px-3">English</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="{{ route('locale.set', 'ar') }}" class="menu-link px-3">العربية</a>
                                    </div>
                                </div>
                            </div>
                            <!--end::Language menu-->

                            @auth
                                <!--begin::User menu-->
                                <div class="app-navbar-item ms-3" id="kt_header_user_menu_toggle">
                                    <div class="cursor-pointer symbol symbol-35px symbol-circle" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                        <span class="symbol-label bg-primary text-inverse-primary fw-bold">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-275px py-4" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <div class="symbol symbol-40px me-4">
                                                    <span class="symbol-label bg-primary text-inverse-primary fw-bold fs-4">{{ mb_substr(Auth::user()->name, 0, 1) }}</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold d-flex align-items-center fs-6">{{ Auth::user()->name }}</span>
                                                    <span class="fw-semibold text-muted fs-7">{{ Auth::user()->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-3">
                                            <a href="{{ route('logout') }}" class="menu-link px-3" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--end::User menu-->
                            @endauth

                            @guest
                                <div class="app-navbar-item ms-3">
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-primary">{{ __('Login') }}</a>
                                </div>
                            @endguest
                        </div>
                        <!--end::Navbar-->
                    </div>
                    <!--end::Header wrapper-->
                </div>
            </div>
            <!--end::Header-->

            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                <!--begin::Sidebar-->
                <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                    <!--begin::Logo-->
                    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
                        <a href="{{ url('/') }}">
                            <img alt="{{ config('app.name') }}" src="{{ asset('assets/media/logos/default-dark.svg') }}" class="h-25px app-sidebar-logo-default" />
                            <img alt="{{ config('app.name') }}" src="{{ asset('assets/media/logos/default-small.svg') }}" class="h-20px app-sidebar-logo-minimize" />
                        </a>
                        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
                            {!! getIcon('black-left-line', 'fs-2 rotate-180') !!}
                        </div>
                    </div>
                    <!--end::Logo-->

                    <!--begin::Sidebar menu-->
                    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
                        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
                            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                                    @foreach ($sidebarMenu as $item)
                                        @php
                                            $itemActive = $isMenuItemActive($item);
                                            $hasChildren = !empty($item['children']);
                                        @endphp
                                        <div class="menu-item {{ $hasChildren ? 'menu-accordion' : '' }} {{ $itemActive ? 'here show' : '' }}" @if($hasChildren) data-kt-menu-trigger="click" @endif>
                                            @if ($hasChildren)
                                                <span class="menu-link">
                                                    <span class="menu-icon">{!! getIcon($item['icon'], 'fs-2') !!}</span>
                                                    <span class="menu-title">{{ $item['title'] }}</span>
                                                    <span class="menu-arrow"></span>
                                                </span>
                                                <div class="menu-sub menu-sub-accordion">
                                                    @foreach ($item['children'] as $child)
                                                        @php
                                                            $childActive = $isMenuItemActive($child);
                                                        @endphp
                                                        <div class="menu-item">
                                                            <a class="menu-link {{ $childActive ? 'active' : '' }}" href="{{ $child['route'] ?? '#' }}">
                                                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                                                <span class="menu-title">{{ $child['title'] }}</span>
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <a class="menu-link {{ $itemActive ? 'active' : '' }}" href="{{ $item['route'] ?? '#' }}">
                                                    <span class="menu-icon">{!! getIcon($item['icon'], 'fs-2') !!}</span>
                                                    <span class="menu-title">{{ $item['title'] }}</span>
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach

                                    @stack('sidebar-extra-items')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Sidebar menu-->

                    @hasSection('sidebar-footer')
                        <div class="app-sidebar-footer flex-column-auto pt-5 pb-6 px-6" id="kt_app_sidebar_footer">
                            @yield('sidebar-footer')
                        </div>
                    @endif
                </div>
                <!--end::Sidebar-->

                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        @hasSection('toolbar')
                            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                                <div class="app-container container-fluid d-flex flex-stack">
                                    @yield('toolbar')
                                </div>
                            </div>
                        @endif

                        <div class="app-content flex-column-fluid" id="kt_app_content">
                            <div class="app-container container-fluid">
                                @if (session('status'))
                                    <div class="alert alert-success">{{ session('status') }}</div>
                                @endif

                                @yield('content')
                            </div>
                        </div>
                    </div>

                    <!--begin::Footer-->
                    <div id="kt_app_footer" class="app-footer py-3 py-lg-6">
                        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-stack py-3">
                            <div class="text-gray-900 order-2 order-md-1">
                                <span class="text-muted fw-semibold me-1">&copy; {{ now()->year }}</span>
                                <a href="{{ config('app.url', url('/')) }}" class="text-gray-800 text-hover-primary">{{ config('app.name') }}</a>
                            </div>
                            <div class="nav nav-dark order-1 order-md-2">
                                @yield('footer-nav')
                            </div>
                        </div>
                    </div>
                    <!--end::Footer-->
                </div>
                <!--end::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
    </div>

    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        {!! getIcon('arrow-up', 'fs-2') !!}
    </div>

    @stack('modals')

    @stack('before-vendor-scripts')

    <!--begin::Vendor Javascript-->
    @foreach (getVendors('js') as $path)
        <script src="{{ filter_var($path, FILTER_VALIDATE_URL) ? $path : asset($path) }}"></script>
    @endforeach
    <!--end::Vendor Javascript-->

    <!--begin::Global Javascript Bundle-->
    @foreach (getGlobalAssets('js') as $path)
        <script src="{{ asset($path) }}"></script>
    @endforeach
    <!--end::Global Javascript Bundle-->

    <!--begin::Custom Javascript-->
    @foreach (getCustomJs() as $path)
        <script src="{{ asset($path) }}"></script>
    @endforeach
    <!--end::Custom Javascript-->

    @stack('scripts')
</body>
</html>

