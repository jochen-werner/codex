var __extends = (this && this.__extends) || function (d, b) {
    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
    function __() { this.constructor = d; }
    __.prototype = b.prototype;
    d.prototype = new __();
};
define(["require", "exports", 'jquery', 'eventemitter2', 'util', "jquery-utils"], function (require, exports, $, EventEmitter2, util_1) {
    var $body = $('body');
    var $header = $('body > .page-header');
    var defaults = {
        breakpoints: {
            'screen-lg-med': "1260px",
            'screen-lg-min': "1200px",
            'screen-md-max': "1199px",
            'screen-md-min': "992px",
            'screen-sm-max': "991px",
            'screen-sm-min': "768px",
            'screen-xs-max': "767px",
            'screen-xs-min': "480px"
        },
        slimscroll: {
            allowPageScroll: false,
            size: '6px',
            color: '#000',
            wrapperClass: 'slimScrollDiv',
            railColor: '#222',
            position: 'right',
            height: '200px',
            alwaysVisible: false,
            railVisible: true,
            disableFadeOut: true
        },
        events: {
            wildcard: true,
            delimiter: ':',
            maxListeners: 100,
            newListener: true
        },
        sidebar: {
            resolveActive: true
        },
        material: {
            "input": true,
            "ripples": false,
            "checkbox": true,
            "togglebutton": true,
            "radio": true,
            "arrive": true,
            "autofill": false,
            "withRipples": [
                ".btn:not(.btn-link)",
                ".card-image",
                ".navbar a:not(.withoutripple)",
                ".dropdown-menu a",
                ".nav-tabs a:not(.withoutripple)",
                ".withripple"
            ].join(","),
            "inputElements": "input.form-control, textarea.form-control, select.form-control",
            "checkboxElements": ".checkbox > label > input[type=checkbox]",
            "togglebuttonElements": ".togglebutton > label > input[type=checkbox]",
            "radioElements": ".radio > label > input[type=radio]"
        },
        highlightjs: {
            theme: 'tomorrow'
        }
    };
    var App = (function (_super) {
        __extends(App, _super);
        function App(opts) {
            if (opts === void 0) { opts = {}; }
            _super.call(this, App.defaults.events);
            var self = this;
            App.config = $.extend(true, App.defaults, opts);
            App.browser.ie8 = !!navigator.userAgent.match(/MSIE 8.0/);
            App.browser.ie9 = !!navigator.userAgent.match(/MSIE 9.0/);
            App.browser.ie10 = !!navigator.userAgent.match(/MSIE 10.0/);
            var resize;
            $(window).resize(function () {
                if (resize) {
                    clearTimeout(resize);
                }
                resize = setTimeout(function () {
                    self.emit('resize');
                }, 50);
            });
        }
        App.prototype.handlePreferences = function () {
            var self = this;
            if ($('#preferences').size() > 0) {
                require(['preferences'], function (preferences) {
                    var prefs = new preferences.Preferences(self, $('#preferences'));
                    prefs.add('page-boxed', 'Page')
                        .createSelectControl()
                        .setOptions({ normal: 'Normal', boxed: 'Boxed' })
                        .setDefault('normal')
                        .make(function (val, pref, $el) {
                        console.log('page-boxed onChange', arguments);
                        $body.ensureClass('page-boxed', val === 'boxed');
                        var $els = $('.page-container, .page-footer');
                        var $headerInner = $('.page-header .page-header-inner');
                        // reset
                        $headerInner.removeClass('container');
                        if ($els.parent('.container').length === 1) {
                            $els.insertAfter('body > .clearfix');
                        }
                        $els.parent('body > .container').remove();
                        if (val === 'boxed') {
                            prefs.getControl('page-footer').setValue('normal');
                            $headerInner.ensureClass('container');
                            var $container = util_1.cre().addClass('container');
                            $('body > .clearfix').after($container);
                            $els.appendTo($container);
                        }
                    });
                    prefs.add('page-header', 'Header')
                        .createSelectControl()
                        .setOptions({ normal: 'Normal', fixed: 'Fixed' })
                        .setDefault('normal')
                        .make(function (val, pref, $el) {
                        console.log('page-header onChange', arguments);
                        $body.ensureClass('page-header-fixed', val === 'fixed');
                        $header.ensureClass('navbar-fixed-top', val === 'fixed');
                    });
                    prefs.add('page-footer', 'Footer')
                        .createSelectControl()
                        .setOptions({ normal: 'Normal', fixed: 'Fixed' })
                        .setDefault('normal')
                        .make(function (val, pref, $el) {
                        console.log('page-footer onChange', arguments);
                        $body.ensureClass('page-footer-fixed', val === 'fixed');
                    });
                    prefs.add('sidebar-side', 'Sidebar position')
                        .createSelectControl()
                        .setOptions({ left: 'Left', right: 'Right' })
                        .setDefault('left')
                        .make(function (val, pref, $el) {
                        console.log('page-footer onChange', arguments);
                        $body.ensureClass('page-sidebar-reversed', val === 'right');
                    });
                    prefs.add('sidebar', 'Sidebar')
                        .createSelectControl()
                        .setOptions({ normal: 'Normal', fixed: 'Fixed', hover: 'Hover icons' })
                        .setDefault('normal')
                        .make(function (val, pref, $el) {
                        console.log('sidebar onChange', arguments);
                        $body.ensureClass('page-sidebar-fixed', val === 'fixed');
                        $body.ensureClass('page-sidebar-closed', val === 'hover');
                        $('.page-sidebar-menu').ensureClass('page-sidebar-menu-closed', val === 'hover');
                    });
                    prefs.init();
                });
            }
        };
        App.prototype.init = function () {
            var self = this;
            console.log('Starting app');
            $('.bs-component [data-toggle="popover"]').popover();
            $('.bs-component [data-toggle="tooltip"]').tooltip();
            $.material.options = App.config.material;
            $.material.init();
            this.handleHeader();
            this.handleFixedSidebar();
            this.handleSidebarMenu();
            this.handleSidebarToggler();
            this.on('resize', this.handleFixedSidebar);
            this.handleSidebarMenuActiveLink();
            this.handleGoTop();
            this.handlePreferences();
            this.initHighlight();
        };
        App.isIE = function (version) {
            if (version === void 0) { version = 0; }
            if (version === 0) {
                if (App.browser.ie8 || App.browser.ie9 || App.browser.ie10) {
                    return true;
                }
            }
            else if (version === 8) {
                return App.browser.ie8;
            }
            else if (version === 9) {
                return App.browser.ie9;
            }
            else if (version === 10) {
                return App.browser.ie10;
            }
        };
        /**
         * Returns the view port
         * @returns {{width: *, height: *}}
         */
        App.getViewPort = function () {
            var e = window, a = 'inner';
            if (!('innerWidth' in window)) {
                a = 'client';
                e = document.documentElement || document.body;
            }
            return {
                width: e[a + 'Width'],
                height: e[a + 'Height']
            };
        };
        /**
         * Checks if the current device is a touch device
         * @returns {boolean}
         */
        App.isTouchDevice = function () {
            try {
                document.createEvent("TouchEvent");
                return true;
            }
            catch (e) {
                return false;
            }
        };
        /**
         * Generates a random ID
         * @param {Number} length
         * @returns {string}
         */
        App.getRandomId = function (length) {
            if (!_.isNumber(length)) {
                length = 15;
            }
            var text = "";
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < length; i++) {
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            }
            return text;
        };
        App.getBreakpoint = function (which) {
            return parseInt(App.config.breakpoints['screen-' + which + '-min'].replace('px', ''));
        };
        App.scrollTo = function (el, offset) {
            var $el = typeof (el) === 'string' ? $(el) : el;
            var pos = ($el && $el.size() > 0) ? $el.offset().top : 0;
            if ($el) {
                if ($('body').hasClass('page-header-fixed')) {
                    pos = pos - $('.page-header').height();
                }
                else if ($('body').hasClass('page-header-top-fixed')) {
                    pos = pos - $('.page-header-top').height();
                }
                else if ($('body').hasClass('page-header-menu-fixed')) {
                    pos = pos - $('.page-header-menu').height();
                }
                pos = pos + (offset ? offset : -1 * $el.height());
            }
            $('html,body').animate({
                scrollTop: pos
            }, 'slow');
        };
        App.scrollTop = function () {
            App.scrollTo();
        };
        App.initSlimScroll = function (el, opts) {
            if (opts === void 0) { opts = {}; }
            var $el = typeof (el) === 'string' ? $(el) : el;
            require(['slimscroll'], function () {
                $el.each(function () {
                    if ($(this).attr("data-initialized")) {
                        return; // exit
                    }
                    var height = $(this).attr("data-height") ? $(this).attr("data-height") : $(this).css('height');
                    var data = _.merge(App.config.slimscroll, $(this).data(), { height: height });
                    $(this).slimScroll($.extend(true, data, opts));
                    $(this).attr("data-initialized", "1");
                });
            });
        };
        App.destroySlimScroll = function (el) {
            var $el = typeof (el) === 'string' ? $(el) : el;
            $el.each(function () {
                if ($(this).attr("data-initialized") === "1") {
                    $(this).removeAttr("data-initialized");
                    $(this).removeAttr("style");
                    var attrList = {};
                    // store the custom attribures so later we will reassign.
                    if ($(this).attr("data-handle-color")) {
                        attrList["data-handle-color"] = $(this).attr("data-handle-color");
                    }
                    if ($(this).attr("data-wrapper-class")) {
                        attrList["data-wrapper-class"] = $(this).attr("data-wrapper-class");
                    }
                    if ($(this).attr("data-rail-color")) {
                        attrList["data-rail-color"] = $(this).attr("data-rail-color");
                    }
                    if ($(this).attr("data-always-visible")) {
                        attrList["data-always-visible"] = $(this).attr("data-always-visible");
                    }
                    if ($(this).attr("data-rail-visible")) {
                        attrList["data-rail-visible"] = $(this).attr("data-rail-visible");
                    }
                    $(this).slimScroll({
                        wrapperClass: ($(this).attr("data-wrapper-class") ? $(this).attr("data-wrapper-class") : 'slimScrollDiv'),
                        destroy: true
                    });
                    var the = $(this);
                    // reassign custom attributes
                    $.each(attrList, function (key, value) {
                        the.attr(key, value);
                    });
                }
            });
        };
        App.highlight = function (code, lang, wrap, wrapPre) {
            if (wrap === void 0) { wrap = false; }
            if (wrapPre === void 0) { wrapPre = false; }
            var defer = $.Deferred();
            require(['highlightjs', 'css!highlightjs-css/' + App.config.highlightjs.theme], function (hljs) {
                var highlighted;
                if (lang && hljs.getLanguage(lang)) {
                    highlighted = hljs.highlight(lang, code).value;
                }
                else {
                    highlighted = hljs.highlightAuto(code).value;
                }
                if (wrap) {
                    highlighted = '<code class="hljs">' + highlighted + '</code>';
                }
                if (wrapPre) {
                    highlighted = '<pre>' + highlighted + '</pre>';
                }
                defer.resolve(highlighted);
            });
            return defer.promise();
        };
        App.prototype.initHighlight = function () {
            var $els = $('pre code:not(.hljs)');
            if ($els.length) {
                $els.each(function () {
                    var $el = $(this);
                    var html = $el.get(0).innerHTML;
                    App.highlight(html).then(function (highlighted) {
                        $el.html(highlighted).ensureClass('hljs');
                    });
                });
            }
        };
        App.prototype.handleSidebarMenuActiveLink = function () {
            if (App.config.sidebar.resolveActive !== true)
                return;
            var currentPath = util_1.trim(location.pathname.toLowerCase(), '/');
            var md = App.getBreakpoint('md');
            if (App.getViewPort().width < md) {
                return; // not gonna do this for small devices
            }
            $('.page-sidebar-menu').find('li > a').each(function () {
                var href = this.getAttribute('href');
                if (!_.isString(href)) {
                    return;
                }
                href = util_1.trim(href).replace(location['origin'], '');
                var path = util_1.trim(href, '/');
                if (path == currentPath) {
                    console.log('found result', this);
                    var $el = $(this);
                    $el.parent('li').not('.active').addClass('active');
                    var $parentsLi = $el.parents('li').addClass('open');
                    $parentsLi.find('.arrow').addClass('open');
                    $parentsLi.has('ul').children('ul').show();
                    return false;
                }
            });
        };
        App.prototype.handleSidebarMenu = function () {
            var self = this;
            $('.page-sidebar').on('click', 'li > a', function (e) {
                if (App.getViewPort().width >= App.getBreakpoint('md') && $(this).parents('.page-sidebar-menu-hover-submenu').size() === 1) {
                    return;
                }
                if ($(this).next().hasClass('sub-menu') === false) {
                    if (App.getViewPort().width < App.getBreakpoint('md') && $('.page-sidebar').hasClass("in")) {
                        $('.page-header .responsive-toggler').click();
                    }
                    return;
                }
                if ($(this).next().hasClass('sub-menu always-open')) {
                    return;
                }
                var parent = $(this).parent().parent();
                var the = $(this);
                var menu = $('.page-sidebar-menu');
                var sub = $(this).next();
                var autoScroll = menu.data("auto-scroll");
                var slideSpeed = parseInt(menu.data("slide-speed"));
                var keepExpand = menu.data("keep-expanded");
                if (keepExpand !== true) {
                    parent.children('li.open').children('a').children('.arrow').removeClass('open');
                    parent.children('li.open').children('.sub-menu:not(.always-open)').slideUp(slideSpeed);
                    parent.children('li.open').removeClass('open');
                }
                var slideOffeset = -200;
                if (sub.is(":visible")) {
                    $('.arrow', $(this)).removeClass("open");
                    $(this).parent().removeClass("open");
                    sub.slideUp(slideSpeed, function () {
                        if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                            if ($('body').hasClass('page-sidebar-fixed')) {
                                menu.slimScroll({
                                    'scrollTo': (the.position()).top
                                });
                            }
                            else {
                                App.scrollTo(the, slideOffeset);
                            }
                        }
                    });
                }
                else {
                    $('.arrow', $(this)).addClass("open");
                    $(this).parent().addClass("open");
                    sub.slideDown(slideSpeed, function () {
                        if (autoScroll === true && $('body').hasClass('page-sidebar-closed') === false) {
                            if ($('body').hasClass('page-sidebar-fixed')) {
                                menu.slimScroll({
                                    'scrollTo': (the.position()).top
                                });
                            }
                            else {
                                App.scrollTo(the, slideOffeset);
                            }
                        }
                    });
                }
                e.preventDefault();
            });
            // handle scrolling to top on responsive menu toggler click when header is fixed for mobile view
            $(document).on('click', '.page-header-fixed-mobile .responsive-toggler', function () {
                App.scrollTop();
            });
        };
        App.prototype._calculateFixedSidebarViewportHeight = function () {
            var self = this;
            var sidebarHeight = App.getViewPort().height - $('.page-header').outerHeight() - 30;
            if ($('body').hasClass("page-footer-fixed")) {
                sidebarHeight = sidebarHeight - $('.page-footer').outerHeight();
            }
            return sidebarHeight;
        };
        App.prototype.handleFixedSidebar = function () {
            var self = this;
            var menu = $('.page-sidebar-menu');
            App.destroySlimScroll(menu);
            menu.parent().find('.slimScrollDiv, .slimScrollBar, .slimScrollRail').remove();
            if ($('.page-sidebar-fixed').size() === 0) {
                return;
            }
            if (App.getViewPort().width >= App.getBreakpoint('md')) {
                menu.attr("data-height", self._calculateFixedSidebarViewportHeight());
                App.initSlimScroll(menu);
                $('.page-content').css('min-height', self._calculateFixedSidebarViewportHeight() + 'px');
            }
        };
        // Handles sidebar toggler to close/hide the sidebar.
        App.prototype.handleFixedSidebarHoverEffect = function () {
            var self = this;
            var body = $('body');
            if (body.hasClass('page-sidebar-fixed')) {
                $('.page-sidebar').on('mouseenter', function () {
                    if (body.hasClass('page-sidebar-closed')) {
                        $(this).find('.page-sidebar-menu').removeClass('page-sidebar-menu-closed');
                    }
                }).on('mouseleave', function () {
                    if (body.hasClass('page-sidebar-closed')) {
                        $(this).find('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
                    }
                });
            }
        };
        App.prototype.handleSidebarToggler = function () {
            var self = this;
            var body = $('body');
            if ($.cookie && $.cookie('sidebar_closed') === '1' && App.getViewPort().width >= App.getBreakpoint('md')) {
                $('body').addClass('page-sidebar-closed');
                $('.page-sidebar-menu').addClass('page-sidebar-menu-closed');
            }
            // handle sidebar show/hide
            $('body').on('click', '.sidebar-toggler', function (e) {
                var sidebar = $('.page-sidebar');
                var sidebarMenu = $('.page-sidebar-menu');
                $(".sidebar-search", sidebar).removeClass("open");
                if (body.hasClass("page-sidebar-closed")) {
                    body.removeClass("page-sidebar-closed");
                    sidebarMenu.removeClass("page-sidebar-menu-closed");
                    if ($.cookie) {
                        $.cookie('sidebar_closed', '0');
                    }
                }
                else {
                    body.addClass("page-sidebar-closed");
                    sidebarMenu.addClass("page-sidebar-menu-closed");
                    if (body.hasClass("page-sidebar-fixed")) {
                        sidebarMenu.trigger("mouseleave");
                    }
                    if ($.cookie) {
                        $.cookie('sidebar_closed', '1');
                    }
                }
                $(window).trigger('resize');
            });
            self.handleFixedSidebarHoverEffect();
            // handle the search bar close
            $('.page-sidebar').on('click', '.sidebar-search .remove', function (e) {
                e.preventDefault();
                $('.sidebar-search').removeClass("open");
            });
            // handle the search query submit on enter press
            $('.page-sidebar .sidebar-search').on('keypress', 'input.form-control', function (e) {
                if (e.which == 13) {
                    $('.sidebar-search').submit();
                    return false; //<---- Add this line
                }
            });
            // handle the search submit(for sidebar search and responsive mode of the header search)
            $('.sidebar-search .submit').on('click', function (e) {
                e.preventDefault();
                if ($('body').hasClass("page-sidebar-closed")) {
                    if ($('.sidebar-search').hasClass('open') === false) {
                        if ($('.page-sidebar-fixed').size() === 1) {
                            $('.page-sidebar .sidebar-toggler').click(); //trigger sidebar toggle button
                        }
                        $('.sidebar-search').addClass("open");
                    }
                    else {
                        $('.sidebar-search').submit();
                    }
                }
                else {
                    $('.sidebar-search').submit();
                }
            });
            // handle close on body click
            if ($('.sidebar-search').size() !== 0) {
                $('.sidebar-search .input-group').on('click', function (e) {
                    e.stopPropagation();
                });
                $('body').on('click', function () {
                    if ($('.sidebar-search').hasClass('open')) {
                        $('.sidebar-search').removeClass("open");
                    }
                });
            }
        };
        App.prototype.handleHeader = function () {
            var self = this;
            // handle search box expand/collapse
            $('.page-header').on('click', '.search-form', function (e) {
                $(this).addClass("open");
                $(this).find('.form-control').focus();
                $('.page-header .search-form .form-control').on('blur', function (e) {
                    $(this).closest('.search-form').removeClass("open");
                    $(this).unbind("blur");
                });
            });
            // handle hor menu search form on enter press
            $('.page-header').on('keypress', '.hor-menu .search-form .form-control', function (e) {
                if (e.which == 13) {
                    $(this).closest('.search-form').submit();
                    return false;
                }
            });
            // handle header search button click
            $('.page-header').on('mousedown', '.search-form.open .submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).closest('.search-form').submit();
            });
        };
        App.prototype.handleGoTop = function () {
            var self = this;
            var offset = 300;
            var duration = 500;
            if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
                $(window).bind("touchend touchcancel touchleave", function (e) {
                    if ($(this).scrollTop() > offset) {
                        $('.scroll-to-top').fadeIn(duration);
                    }
                    else {
                        $('.scroll-to-top').fadeOut(duration);
                    }
                });
            }
            else {
                $(window).scroll(function () {
                    if ($(this).scrollTop() > offset) {
                        $('.scroll-to-top').fadeIn(duration);
                    }
                    else {
                        $('.scroll-to-top').fadeOut(duration);
                    }
                });
            }
            $('.scroll-to-top').click(function (e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, duration);
                return false;
            });
        };
        App.config = defaults;
        App.defaults = defaults;
        App.browser = {
            ie8: false, ie9: false, ie10: false
        };
        return App;
    })(EventEmitter2);
    exports.App = App;
});
