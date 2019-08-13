var gulp = require( 'gulp' ),
  flog = require('fancy-log'),
  pump = require( 'pump' ),
  babelify = require( 'babelify' ),
  browserify = require( 'browserify' ),
  $ = require( 'gulp-load-plugins' )(),
  browserSync = require( 'browser-sync' ).create();

var AUTOPREFIXER_BROWSERS = [
  "Android >= 4",
  "last 5 Chrome versions",
  "last 5 Firefox versions",
  "Explorer >= 11",
  "iOS >= 6",
  "last 5 Opera versions",
  "last 5 Safari versions",
  "last 5 Edge versions",
  "> 1%"
];

gulp.task( 'uploadedImages', function( callback ){
  pump(
    [
      gulp.src(['../../uploads/**/*.{png,PNG,jpg,JPG,jpeg,JPEG,gif,GIF}'], {
        base: '.'
      }),
      $.imagemin(),
      gulp.dest('./'),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'devImages', function( callback ) {
  pump(
    [
      gulp.src(['assets/src/images/**/*.{png,PNG,jpg,JPG,jpeg,JPEG,gif,GIF}'], {
        cwd: '.'
      }),
      $.imagemin(),
      gulp.dest('assets/dist/images'),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'svgFiles', function( callback ) {
  pump(
    [
      gulp.src( [ 'assets/src/images/**/*.svg' ], {
        cwd: '.'
      } ),
      gulp.dest( 'assets/dist/images' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'fonts', function( callback ) {
  pump(
    [
      gulp.src( ['assets/src/fonts/**/*'], {
        cwd: '.'
      } ),
      gulp.dest( 'assets/dist/fonts' ),
      browserSync.stream()
    ],
    callback()
  );
} );

/*gulp.task( 'jsDeps', function( callback ) {
  pump(
    [
      gulp.src([
        'assets/src/bower_components/jquery/dist/jquery.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap.js', // the whole shebang
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/affix.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/alert.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/button.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/carousel.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/modal.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/scrollspy.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/tooltip.js',
        'assets/src/bower_components/bootstrap-sass/assets/javascripts/bootstrap/popover.js',
        'assets/src/bower_components/waypoints/lib/jquery.waypoints.js'
      ]),
      $.sourcemaps.init(),
      $.concat( 'dependencies.js', {
        newLine: ';'
      } ),
      $.uglify( false ),
      $.rename( 'dependencies.min.js' ),
      $.sourcemaps.write( '.' ),
      gulp.dest( 'assets/dist/js' ),
      browserSync.stream()
    ],
    callback()
  );
} );*/

gulp.task( 'js', function( callback ) {
  pump(
    [
      gulp.src( [
        './assets/src/js/main.js'
      ] ),
      $.tap( function ( file ) {
        flog( 'bundling ' + file.path );
        // replace file contents with browserify's bundle stream
        file.contents = browserify( file.path )
          .transform( 'babelify', {
            presets: [
              [ "env", {
                "targets": {
                  "browsers": AUTOPREFIXER_BROWSERS
                }
              } ]
            ]
          } )
          .bundle();
      }),
      $.buffer(),
      $.sourcemaps.init(),
      $.uglify( false ),
      $.rename( {
        extname: '.min.js'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( './assets/dist/js' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'jsAdmin', function( callback ) {
  pump(
    [
      gulp.src( [
        './admin/assets/src/js/admin.js'
      ] ),
      $.tap( function ( file ) {
        flog( 'bundling ' + file.path );
        // replace file contents with browserify's bundle stream
        file.contents = browserify( file.path )
          .transform( 'babelify', {
            presets: [
              [ "env", {
                "targets": {
                  "browsers": AUTOPREFIXER_BROWSERS
                }
              } ]
            ]
          } )
          .bundle();
      }),
      $.buffer(),
      $.sourcemaps.init(),
      $.uglify( false ),
      $.rename( {
        extname: '.min.js'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( './admin/assets/dist/js' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'jsCustomizer', function( callback ) {
  pump(
    [
      gulp.src( [
        './admin/assets/src/js/customizer.js'
      ] ),
      $.sourcemaps.init(),
      $.uglify( false ),
      $.rename( {
        extname: '.min.js'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( './admin/assets/dist/js' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'css', function( callback ) {
  pump(
    [
      gulp.src( [
        'assets/src/scss/main.scss'
      ] ),
      $.sourcemaps.init(),
      $.sass( {
        outputStyle: 'compressed'
      } ),
      $.autoprefixer(),
      $.rename( {
        extname: '.min.css'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( 'assets/dist/css' ),
      browserSync.stream()
    ],
    callback()
  );
} );

gulp.task( 'cssAdmin', function( callback ) {
  pump(
    [
      gulp.src( [
        'admin/assets/src/scss/admin.scss'
      ] ),
      $.sourcemaps.init(),
      $.sass( {
        outputStyle: 'compressed'
      } ),
      $.autoprefixer(),
      $.rename( {
        extname: '.min.css'
      } ),
      $.sourcemaps.write( '.' ),
      gulp.dest( 'admin/assets/dist/css' ),
      browserSync.stream()
    ],
    callback()
  );
});

gulp.task( 'browserSync', function() {
  browserSync.init( {
    proxy: 'http://cs-csawp:8888/wp/', // change this to match your host
    watchTask: true,
    open: false
  } );
} );

gulp.task( 'watch', function() {
  gulp.watch( [ '*.html', '*.php', 'templates/**/*.twig', 'includes/*.php' ] ).on( 'change', browserSync.reload );
  // gulp.watch(['../../uploads/**/*'], ['uploadedImages']);
  gulp.watch( [ 'assets/src/images/**/*.{png,PNG,jpg,JPG,jpeg,JPEG,gif,GIF}', 'assets/src/images/**/*.svg' ], gulp.parallel( 'devImages','svgFiles' ) );
  gulp.watch( [ 'assets/src/fonts/**/*.{eot,otf,svg,ttf,woff,woff2}' ], gulp.series( 'fonts' ) );
  gulp.watch( [ 'assets/src/js/**/*.js', 'admin/assets/src/js/**/*.js', 'templates/**/*.js' ], gulp.parallel( 'js', 'jsAdmin', 'jsCustomizer' ) );
  gulp.watch( [ 'assets/src/scss/**/*.scss', 'admin/assets/src/scss/**/*.scss', 'templates/*.scss', 'templates/**/*.scss' ], gulp.parallel( 'css', 'cssAdmin' ) );
});

//gulp.task( 'build', gulp.series( 'uploadedImages', 'devImages', 'svgFiles', 'fonts', 'jsDeps', 'js', 'css', 'jsAdmin', 'cssAdmin' ) );
gulp.task( 'build', gulp.parallel( 'uploadedImages', 'devImages', 'svgFiles', 'fonts', 'js', 'css', 'jsAdmin', 'jsCustomizer', 'cssAdmin' ) );
gulp.task( 'default', gulp.series( 'build', gulp.parallel( 'browserSync', 'watch' ) ) );
