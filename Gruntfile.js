module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        sass: {
            dist: {
                files: {
                    'src/ext/css/build/common.css': 'src/ext/css/common.scss',
                }
            }
        },

        postcss: {
            options: {
                processors: [
                    require('autoprefixer')({
                        browsers: ['> 0.5%', 'last 2 versions', 'IE 10', 'Firefox ESR', 'Opera 12.1', 'Android 4']
                    }),
                ],
            },
            dist: {
                files: [{
                    src: 'src/ext/css/build/*.css',
                    expand: true
                }]
            }
        },

        cssmin: {
            combine: {
                files: {
                    'src/ext/css/build/common.min.css': [
                        'src/ext/css/build/common.css',
                    ]
                }
            }
        },

        watch: {
            styles: {
                files: ['src/ext/css/*.css', 'src/ext/css/*.scss'],
                tasks: ['build-stylesheets'],
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('build-stylesheets', ['sass', 'postcss', 'cssmin']);

    grunt.registerTask('build-all', ['build-stylesheets']);

    grunt.registerTask('default', ['build-all']);
};
