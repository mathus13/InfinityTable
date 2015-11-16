module.exports = function (grunt) {

    // 1. All configuration goes here 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        bower_concat: {
            all: {
                dest: 'build/_bower.js',
                cssDest: 'build/_bower.css',
                bowerOptions: {
                    relative: false
                }
            }
        },
        concat: {
            dist: {
                src: [
                    'build/_bower.js'
                ],
                dest: 'assets/js/build/production.js',
            }
        },
        uglify: {
            options: {
                sourceMap: true,
                preserveComments: true,
                compress: {
                    dead_code: true,
                    unused: true
                }
            },
            build: {
                src: [
                    'assets/js/libs/jquery.min.js', // All JS in the libs folder
                    'assets/js/libs/*.js', // All JS in the libs folder
                    'assets/js/app.js',  // This specific file
                    'assets/js/app.utils.js',  // This specific file
                    'assets/js/app.socket.js',  // This specific file
                ],
                dest: 'assets/js/build/production.min.js'
            }
        },
        watch: {
            scripts: {
                files: ['assets/js/*.js', 'assets/js/libs/*.js'],
                tasks: ['uglify'],
                options: {
                    spawn: false,
//                    livereload: true,
                },
            }
        },
        cssmin: {
            target: {
                files: {
                    expand: true,
                    cwd: 'public/assets/css',
                    src: ['build/_bower.css', '*.css', '!*.min.css'],
                    dest: 'release/css',
                    ext: '.min.css'
                }
            }
        }

    });

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-bower-concat');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['uglify']);
    grunt.registerTask('dev', ['watch']);

};