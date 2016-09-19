module.exports = function(grunt) {

  	grunt.initConfig({
	  	pkg: grunt.file.readJSON('package.json'),
	    uglify: {
		  options: {
		    // the banner is inserted at the top of the output
		    banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
		  },
		  build: {
		    // the files to minify
		    src: 'js/**/*.js',
		    // the location of the resulting JS file
		    dest: 'dest/<%= pkg.name %>.min.js'
		  }
		},
		jshint: {
			src: ['js/*.js', '!js/bootstrap.js', '!js/jquery-ui.js'],
			options: {
				curly: true,
				undef: true,
				browser: true,
				devel: true,
				jshintrc: true,
				globals: {
					jQuery: false,
					$: false,
					Highcharts: false
				}
			}
		},
		watch: {
			files: ['js/**/*.js'],
			tasks: ['jshint', 'uglify']
		}
	});

	// Load the plugins that provide the tasks we need
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Default task(s).
	grunt.registerTask('default', ['jshint', 'uglify']);
	grunt.registerTask('checkjs', ['jshint']);
	grunt.registerTask('minify', ['uglify']);
};
