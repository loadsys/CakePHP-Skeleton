module.exports = function(grunt) {
	'use strict';

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Install Tasks
		bowercopy: {
			css: {
				options: {
					srcPrefix: 'bower_components',
					destPrefix: 'webroot/css'
				},
				files: {
					'qunit.css': 'bower_components/qunit/qunit/qunit.css'
				}
			}
		},

		// CSS Tasks
		sass: {
			dist: {
				options: {
					loadPath: ['bower_components/foundation-sites/scss'],
				},
				files: {
					'webroot/css/build/app.css': 'webroot/css/src/app.scss'
				}
			}
		},
		postcss: {
			options: {
				map: true,
				sourcesContent: true,
				processors: [
					require('autoprefixer')({
						browsers: ['last 2 versions', 'ie >= 9', 'and_chr >= 2.3']
					}),
					require('cssnano')()
				]
			},
			dist: { src: 'webroot/css/build/*.css' }
		},
		sasslint: {
			options: {
				configFile: '.sass-lint.yml',
				//formatter: 'unix', // Ref: https://github.com/eslint/eslint/tree/master/lib/formatters
				//outputFile: 'tmp/sasslint-report.txt'
			},
			dist: [
				'webroot/css/src/*.scss'
			]
		},

		// JS Tasks
		concat: {
			options: {
				separator: ';',
			},
			main: {
				src: [
					'webroot/js/vendor/jquery.js',
					'bower_components/foundation-sites/js/foundation.core.js',
					'bower_components/foundation-sites/js/foundation.util.mediaQuery.js',
					'bower_components/foundation-sites/js/foundation.drilldown.js',
					'bower_components/foundation-sites/js/foundation.dropdown.js',
					'bower_components/foundation-sites/js/foundation.dropdownMenu.js',
					'bower_components/foundation-sites/js/foundation.equalizer.js',
					'bower_components/foundation-sites/js/foundation.orbit.js',
					'bower_components/foundation-sites/js/foundation.responsiveMenu.js',
					'bower_components/foundation-sites/js/foundation.responsiveToggle.js',
					'bower_components/foundation-sites/js/foundation.tabs.js',
					'bower_components/foundation-sites/js/foundation.toggler.js',
					'bower_components/foundation-sites/js/foundation.util.box.js',
					'bower_components/foundation-sites/js/foundation.util.keyboard.js',
					'bower_components/foundation-sites/js/foundation.util.motion.js',
					'bower_components/foundation-sites/js/foundation.util.nest.js',
					'bower_components/foundation-sites/js/foundation.util.timerAndImageLoader.js',
					'bower_components/foundation-sites/js/foundation.util.touch.js',
					'bower_components/foundation-sites/js/foundation.util.triggers.js',
					'webroot/js/src/init.js',
					'webroot/js/src/app.js',
				],
				dest: 'webroot/js/build/scripts.js'
			},
			tests: {
				src: [
					'webroot/js/vendor/modernizr.js',
					'webroot/js/vendor/jquery.js',
					'webroot/js/init.js',
					'webroot/js/src/order-balance.js',
					'bower_components/qunit/qunit/qunit.js',
					'webroot/js/test/*.js',
				],
				dest: 'webroot/js/build/tests.js'
			}
		},
		uglify: {
			options: {
				mangle: false
			},
			my_target: {
				files: {
					'webroot/js/build/scripts.min.js': ['webroot/js/build/scripts.js'],
				}
			}
		},
		qunit: {
			all: ['webroot/test.html']
		},

		// Developer Tasks
		watch: {
			css: {
				files: ['webroot/css/src/*.scss'],
				tasks: ['sass']
			},
			jss: {
				files: ['webroot/js/test/*.js', 'webroot/js/src/*.js'],
				tasks: ['concat', 'qunit'] //@TODO: The `uglify` task is still technically required here to get `webroot/js/src/*.js` into `webroot/js/build/`. Could be replaced with a simple "copy" command during file watch though.
			}
		}
	});

	grunt.loadNpmTasks('grunt-bowercopy');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-qunit');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-postcss');
	grunt.loadNpmTasks('grunt-sass-lint');

	grunt.registerTask('css', [
		'sass:dist', 'postcss:dist' // build css assets (in order)
	]);
	grunt.registerTask('sasstest', [
		'sasslint:dist' // lint source scss files
	]);
	grunt.registerTask('testjs', [
		'concat', 'uglify', 'qunit' // build js assets and run tests (in order)
	]);
	grunt.registerTask('default', [
		'css', 'testjs'
	]);
};
