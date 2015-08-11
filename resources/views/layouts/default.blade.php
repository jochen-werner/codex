<!DOCTYPE html><!--[if IE 8]>
<html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9"><![endif]-->
<!--[if !IE]><!-->
<html lang="en"><!--<![endif]-->
<head>
    <title>Codex Theme Demo</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link href="{{ asset('vendor/codex/styles/stylesheet.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('vendor/codex/styles/themes/theme-default.css') }}" type="text/css" rel="stylesheet">
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo">
<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-logo">
            <a href="#" class="logo-default">
                <img src="{{ asset('vendor/codex/images/codex.png') }}">
            </a>
            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <div class="page-actions">
            <div class="btn-group">
                <div class="btn-group">
                    <a href="javscript:;" type="button" data-toggle="dropdown" aria-expanded="false" class="btn dropdown-toggle btn-primary btn-sm">
                        @yield('project-name')
                    </a>
                    <ul class="dropdown-menu">
                        @yield('projects')
                    </ul>
                </div>
                <div class="btn-group">
                    <a href="javscript:;" type="button" data-toggle="dropdown" aria-expanded="false" class="btn dropdown-toggle btn-primary btn-sm">
                        @yield('project-version')
                    </a>
                    <ul class="dropdown-menu">
                        @yield('project-versions')
                    </ul>
                </div>
            </div>
        </div>
        <div class="page-top">
            @include('codex::partials/search-form')
        </div>
    </div>
</div>
<div class="clearfix"></div>
<div class="page-container">
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar nav-collapse">
            <ul data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" class="page-sidebar-menu">
                @section('sidebar-menu')
                @show
            </ul>
        </div>
    </div>
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-head">
                <div class="page-title">
                    <h1>@yield('page-title')
                        <small> @yield('page-subtitle', '')</small>
                    </h1>
                </div>
            </div>
            <ul class="page-breadcrumb breadcrumb">
                @section('breadcrumb')
                    <li><a href="index.html">Home</a><i class="fa fa-arrow-right"></i></li>
                @show
            </ul>
            <div class="page-content-inner">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<div class="page-footer">
    <div class="page-footer-inner">Copyright {{ date('Y') }} &copy; {{ config('codex.display_name') }}</div>
    <div class="scroll-to-top"></div>
</div>
@include('codex::partials/preferences')
<script src="{{ asset('vendor/codex/scripts/vendor.min.js') }}"></script>
<script src="{{ asset('vendor/codex/scripts/config.js') }}"></script>
<script>
    require.config({
        baseUrl: '{!! url('vendor/codex') !!}}'
    })
</script>
<script src="{{ asset('vendor/codex/scripts/init.js') }}"></script>
</body>
</html>
