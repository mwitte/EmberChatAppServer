'use strict';

var MergeBuildPropertiesClass = require('./build/MergeBuildProperties');
var propertyMerger = new MergeBuildPropertiesClass('buildDefaultProperties.json', 'buildProperties.json');

// LOAD AND MERGE BUILD PROPERTIES
var buildProperties = propertyMerger.merge();

module.exports = function (grunt) {
    // show elapsed time at the end
    require('time-grunt')(grunt);
    // load all grunt tasks
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        // package info
        pkg: grunt.file.readJSON('package.json'),
        // build properties("buildDefaultProperties.json" merged with "buildProperties.json")
        buildProperties: buildProperties,

        watch: {
            src: {
                files: ['<%= buildProperties.src %>/**/*'],
                tasks: ['deploy']
            }
        },
        clean: {
            dist: {
                files: [{
                    dot: true,
                    src: [
                        '<%= buildProperties.dist %>'
                    ]
                }]
            },
            deploy: {
                files: [{
                    dot: true,
                    src: [
                        '<%= buildProperties.deploydir %>/**/*',
                        '!<%= buildProperties.deploydir %>/vendor/**'
                    ]
                }]
            },
            tmp: '.tmp'
        },
        // Put files not handled in other tasks here
        copy: {
            src: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= buildProperties.src %>',
                    dest: '<%= buildProperties.dist %>',
                    src: '**/*'
                }]
            },
            app: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= buildProperties.app %>',
                    dest: '<%= buildProperties.dist %>',
                    src: '**/*'
                }]
            },
            deploy: {
                files: [{
                    expand: true,
                    dot: true,
                    cwd: '<%= buildProperties.dist %>',
                    dest: '<%= buildProperties.deploydir %>',
                    src: '**/*'
                }]
            }
        },
        curl: {
            // downloads the app
            app: {
                src: '<%= buildProperties.appDistUrl %>',
                dest: '.tmp/appdist.zip'
            }
        },
        unzip: {
            // unzips downloaded app dist package into dist
            appdist: {
                src: '.tmp/appdist.zip',
                dest: '<%= buildProperties.dist %>',
                router: function (filepath) {
                    // remove directory
                    return filepath.replace(buildProperties.appDistDir + '/', '');
                }
            }
        }
    });

    /**
     * Copy the app from set directory, if not found loads last build from github
     */
    grunt.registerTask('requireApp', function(target) {
        var fs = require('fs');
        if (fs.existsSync(buildProperties.app)) {
            console.log('Got app from ' + buildProperties.app);
            grunt.task.run('copy:app');
        }else{
            console.info('App not found under: ' + buildProperties.app);
            console.info('You should specify the path to the app dist');
            console.info('Create a build/buildProperties.json and define the app dir.');
            console.info('Look into the buildDefaultProperties.json for help.');
            console.log('Fallback: Load app from ' + buildProperties.appDistUrl);
            grunt.task.run(['curl:app', 'unzip:appdist', 'clean:tmp']);
        }
    });

    grunt.registerTask('server', function (target) {
        grunt.task.run([
            'deploy',
            'watch'
        ]);
    });

    grunt.registerTask('build', [
        'clean:dist',
        'copy:src',
        'requireApp'
    ]);

    grunt.registerTask('deploy', [
        'build',
        'clean:deploy',
        'copy:deploy'
    ]);

    grunt.registerTask('default', [
        'build'
    ]);
};
