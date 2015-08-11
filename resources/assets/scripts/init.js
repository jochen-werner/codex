requirejs.config({
    baseUrl: "assets/scripts/",
    paths: {
        'jquery': '../plugins/jquery/dist/jquery.min',
        'material': '../plugins/bootstrap-material-design/dist/js/material.min',
        'ripples': '../plugins/bootstrap-material-design/dist/js/ripples.min',
        'bootstrap': '../plugins/bootstrap-sass-official/assets/javascripts/bootstrap.min',
        'snackbar': '../plugins/snackbarjs/dist/snackbar.min',
        'nouislider': '../plugins/nouislider/distribute/nouislider.min',
        'slimscroll': '../plugins/jquery-slimscroll/jquery.slimscroll.min',
        'eventemitter2': '../plugins/eventemitter2/lib/eventemitter2',
        'ace': '../plugins/ace/lib/ace',
        'highlightjs': '../plugins/highlightjs/highlight.pack',
        'highlightjs-css': '../plugins/highlightjs/styles'
    },
    map: {
        '*': {
            'css': '../plugins/require-css/css.min' // or whatever the path to require-css is
        }
    },
    shim: {
        'jquery': { exports: '$' },
        'highlightjs': { exports: 'hljs' },
        'material': ['jquery'],
        'ripples': ['jquery', 'material'],
        'snackbar': ['jquery'],
        'nouislider': ['jquery'],
        'bootstrap': ['jquery'],
        'slimscroll': ['jquery']
    },
    waitSeconds: 5,
    config: {
        debug: true
    }
});
requirejs(['app', 'jquery', 'bootstrap', 'material'], function (app, $) {
    console.log(arguments);
    var application = new app.App({});
    $(function () {
        application.init();
    });
    // temporary
    window['App'] = app.App;
    window['application'] = application;
});
