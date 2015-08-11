var util  = require('util'),
    path  = require('path');
var _     = require('lodash'),
    exec  = require('child_process').exec,
    grunt = require('grunt');

var installBower = function(cb){
    exec('bower install', {
        cwd: 'theme'
    }, cb)
}

module.exports = function (_grunt) {
    grunt = _grunt;

    var config = {
        copy      : {
            assets: {src: ['**'], cwd: 'theme/dist/assets', expand: true, dest: 'resources/assets/'}
        },
        clean: {
            assets: {src: 'resources/assets'}
        },
        subgrunt: {
            build: {
                options: {
                    npmInstall: true,
                },
                projects: {
                    'theme': ['build', '--target=dist']
                }
            }
        }
    };

    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);

    grunt.initConfig(config);
    grunt.registerTask('bower-install', function(){
        var done = this.async();
        installBower(function(err, out, stderr){
            if(err){
                grunt.fail.fatal(err);
            }
            grunt.log.ok(out);
            done();
        });
    });
    grunt.registerTask('build', ['bower-install', 'subgrunt:build', 'clean:assets', 'copy:assets']);
};
