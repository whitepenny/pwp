var fs             = require('fs');
var gulp           = require('gulp');
var autoprefixer   = require('gulp-autoprefixer');
var cleanCSS       = require('gulp-clean-css');
var filter         = require('gulp-filter');
var include        = require('gulp-include');
var jshint         = require('gulp-jshint');
var less           = require('gulp-less');
var modernizr      = require('gulp-modernizr');
var plumber        = require('gulp-plumber');
var realFavicon    = require('gulp-real-favicon');
var sequence       = require('gulp-sequence');
var sourcemaps     = require('gulp-sourcemaps');
var svgSprite      = require('gulp-svg-sprites');
var svg2png        = require('gulp-svg2png');
var uglify         = require('gulp-uglify');

var lessImportNPM  = require('less-plugin-npm-import');

// Sprites
gulp.task('sprites', function () {
  return gulp.src('./assets/icons/*.svg')
    .pipe(plumber())
    .pipe(svgSprite({
      preview: false,
      cssFile: '../assets/less/imports/sprite.less',
      svg: {
        sprite: 'images/sprite.svg'
      },
      padding: 5
    }))
    .pipe(gulp.dest('./public'))
    .pipe(filter('**/*.svg'))
    .pipe(svg2png())
    .pipe(gulp.dest('./public'));
});

// Images
gulp.task('images', function () {
  return gulp.src('./assets/images/**/*')
    .pipe(gulp.dest('./public/images'))
    .pipe(filter('**/*.svg'))
    .pipe(svg2png())
    .pipe(gulp.dest('./public/images'));
});

// Less
gulp.task('styles', function() {
  return gulp.src('./assets/less/*.less')
    .pipe(plumber())
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(less({
      plugins: [ new lessImportNPM() ]
    }))
    .pipe(autoprefixer({
      browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1', 'ie >= 10']
    }))
    .pipe(cleanCSS({
      compatibility: 'ie10',
      inline: ['none']
    }))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./public/css'));
});

// JS
gulp.task('scripts', function() {
  return gulp.src('./assets/js/*.js')
    .pipe(plumber())
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(include({
      includePaths: [
        __dirname + '/assets/js',
        __dirname + '/node_modules'
      ]
    }))
    .pipe(jshint())
    .pipe(uglify())
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('./public/js'));
});

// Modernizr
gulp.task('modernizr', function () {
  return gulp.src(['./public/js/*.js', './public/css/*.css'])
    .pipe(plumber())
    .pipe(modernizr({
      'tests': [
        'js',
        'touchevents'
      ],
      'options': [
        'setClasses',
        'addTest',
        'testProp',
        'fnBind'
      ]
    }))
    .pipe(uglify())
    .pipe(gulp.dest('./public/js/'))
});

// Favicon
gulp.task('favicon', function () {
  // File where the favicon markups are stored
  var FAVICON_DATA_FILE = './assets/faviconData.json';

  return realFavicon.generateFavicon({
		masterPicture: './assets/favicon.png',
		dest: './public/favicons/',
    iconsPath: 'public/favicons/',
		design: {
			ios: {
				pictureAspect: 'noChange',
				assets: {
					ios6AndPriorIcons: false,
					ios7AndLaterIcons: false,
					precomposedIcons: false,
					declareOnlyDefaultIcon: true
				}
			},
			desktopBrowser: {},
			windows: {
				pictureAspect: 'noChange',
				backgroundColor: '#000000',
				onConflict: 'override',
				assets: {
					windows80Ie10Tile: false,
					windows10Ie11EdgeTiles: {
						small: false,
						medium: true,
						big: false,
						rectangle: false
					}
				}
			},
			androidChrome: {
				pictureAspect: 'backgroundAndMargin',
				margin: '17%',
				backgroundColor: '#000000',
				themeColor: '#000000',
				manifest: {
					name: 'PWP Video',
					display: 'standalone',
					orientation: 'notSet',
					onConflict: 'override',
					declared: true
				},
				assets: {
					legacyIcon: false,
					lowResolutionIcons: false
				}
			},
			safariPinnedTab: {
				pictureAspect: 'blackAndWhite',
				threshold: 42.96875,
				themeColor: '#5bbad5'
			}
		},
		settings: {
			scalingAlgorithm: 'Mitchell',
			errorOnImageTooSmall: false,
			readmeFile: false,
			htmlCodeFile: false,
			usePathAsIs: false
		},
		markupFile: FAVICON_DATA_FILE
	}, function() {
	});
});


gulp.task('default', sequence(
  ['favicon', 'images', 'sprites', 'styles', 'scripts'],
  ['modernizr']
));

gulp.task('watch', ['default'], function() {
  gulp.watch('assets/less/**/*.less', { cwd: './' }, ['styles']);
  gulp.watch('assets/js/**/*.js', { cwd: './' }, ['scripts']);
  gulp.watch('assets/icons/*.svg', { cwd: './' }, ['sprites']);
  gulp.watch('assets/images/*', { cwd: './' }, ['images']);
});
