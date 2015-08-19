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
    <script>
        window['CodexLoader'] = (function () {
            return {
                loaders    : {},
                start: function (id, pre) {
                    this.loaders[id] = {
                        el    : document.getElementById(id),
                        loader: document.createElement('div'),
                        inner : document.createElement('div'),
                        pre: pre
                    };
                    this.loaders[id].el.classList.add(pre + '-loader-content');
                    this.loaders[id].el.parentNode.classList.add(pre + '-loading');
                    this.loaders[id].loader.classList.add(pre + '-loader');
                    this.loaders[id].inner.classList.add('loader', 'loader-' + pre);
                    this.loaders[id].loader.appendChild(this.loaders[id].inner);
                    this.loaders[id].el.parentNode.appendChild(this.loaders[id].loader);
                },
                stop : function (id) {
                    var pre = this.loaders[id].pre;
                    this.loaders[id].el.classList.remove(pre + '-loader-content');
                    this.loaders[id].el.parentNode.classList.remove(pre + '-loading');
                    this.loaders[id].loader.remove();
                    delete this.loaders[id];
                }
            };
        }.call());
    </script>
    <link href="{{ asset('vendor/codex/styles/stylesheet.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('vendor/codex/styles/themes/theme-default.css') }}" type="text/css" rel="stylesheet">
    @stack('stylesheets')
</head>

@section('body-element')
<body class="page-loading page-header-fixed page-sidebar-closed-hide-logo">
@show

@section('page-loader')
<div id="page-loader">
    <div class="loader loader-page"></div>
</div>
@show

<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-logo">
            <a href="#" class="logo-default">
                <img src="{{ asset('vendor/codex/images/codex.png') }}">
            </a>

            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <div class="page-actions">
            @section('header-actions')
            @show
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
                    <h1>@yield('pageTitle')
                        <small> @yield('pageSubtitle', '')</small>
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

@section('preferences')
    @include('codex::partials/preferences')
@show

<script src="{{ asset('vendor/codex/scripts/vendor.min.js') }}"></script>
<script src="{{ asset('vendor/codex/scripts/config.js') }}"></script>

<script>
    require.config({
        baseUrl: '{!! url('vendor/codex/scripts') !!}'
    })
</script>

@stack('config-scripts')

@section('init-script')
    <script>
        require(['app', 'jquery'], function (app, $) {
            window['app'] = app.instance;
            /** @var {App} app */
            $(function () {
                app = app.instance;
                app.init({});
                @if(isset($project))
                app.setProjectConfig({!! json_encode($project->config()) !!});
                @endif
                @if(isset($document))
                app.setDocumentAttributes({!! json_encode($document->attr()) !!});
                @endif
            });
        })
    </script>
@show

@section('remove-loader-script')
    <script>
        require(['app', 'jquery'], function (app, $) {
            $(function () {
                window['app'] = app.instance.removePageLoader();
            });
        });
    </script>
@show

@stack('init-scripts')

</body>
</html>
