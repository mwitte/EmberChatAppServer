'use strict';

var MergeBuildPropertiesClass = require('./build/MergeBuildProperties');
var propertyMerger = new MergeBuildPropertiesClass('buildDefaultProperties.json', 'buildProperties.json');

// LOAD AND MERGE BUILD PROPERTIES
var buildProperties = propertyMerger.merge();

// check if path to appshim got set
if(buildProperties.app === "/path/to/your/appshim"){
    console.error('\n\nYou have to specify the correct path to your appshim!\n');
    console.info('Create a buildProperties.json and define the app dir.');
    console.info('Look into the buildDefaultProperties.json for help.\n\n');
    return;
}


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
                        '<%= buildProperties.deploydir %>'
                    ]
                }]
            }
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
        'copy:app'
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
