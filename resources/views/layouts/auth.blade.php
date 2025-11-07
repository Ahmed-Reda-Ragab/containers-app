@php
    \App\Core\KTBootstrap::init();

    $authBootstrapper = config('settings.KT_THEME_BOOTSTRAP.auth');

    if ($authBootstrapper && class_exists($authBootstrapper)) {
        app($authBootstrapper)->init();
    }
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

    <div class="d-flex flex-column flex-root" id="kt_app_root">
        @hasSection('auth-background')
            @yield('auth-background')
        @else
            <style>
                body {
                    background-image: url('{{ asset('assets/media/auth/bg4.jpg') }}');
                }

                [data-bs-theme="dark"] body {
                    background-image: url('{{ asset('assets/media/auth/bg4-dark.jpg') }}');
                }
            </style>
        @endif

        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <!--begin::Aside-->
            <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
                <div class="d-flex flex-center flex-lg-start flex-column text-center text-lg-start">
                    @hasSection('auth-aside')
                        @yield('auth-aside')
                    @else
                        <a href="{{ url('/') }}" class="mb-7">
                            <img alt="{{ config('app.name') }}" src="{{ asset('assets/media/logos/custom-3.svg') }}" />
                        </a>
                        <h2 class="text-white fw-normal m-0">{{ __('Branding tools designed for your business') }}</h2>
                    @endif
                </div>
            </div>
            <!--end::Aside-->

            <!--begin::Body-->
            <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
                <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20 shadow-lg">
                    <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20 w-100">
                        @if (session('status'))
                            <div class="alert alert-success w-100">{{ session('status') }}</div>
                        @endif

                        @yield('content')
                    </div>
                    <div class="d-flex flex-stack px-lg-10 w-100">
                        @hasSection('auth-footer')
                            @yield('auth-footer')
                        @else
                            <div class="d-flex align-items-center">
                                <span class="text-muted fw-semibold fs-7 me-3">{{ __('Language') }}:</span>
                                <div class="btn-group">
                                    <a href="{{ route('locale.set', 'en') }}" class="btn btn-sm btn-light{{ app()->getLocale() === 'en' ? ' btn-active-primary' : '' }}">English</a>
                                    <a href="{{ route('locale.set', 'ar') }}" class="btn btn-sm btn-light{{ app()->getLocale() === 'ar' ? ' btn-active-primary' : '' }}">العربية</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!--end::Body-->
        </div>
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

