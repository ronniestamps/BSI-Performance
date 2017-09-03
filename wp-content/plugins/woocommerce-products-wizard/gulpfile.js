var autoPrefixBrowserList = ['last 2 version', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4'];

var gulp = require('gulp');
var gutil = require('gulp-util');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');
var minifyCSS = require('gulp-minify-css');
var autoprefixer = require('gulp-autoprefixer');
var plumber = require('gulp-plumber');
var babel = require('gulp-babel');
var rename = require('gulp-rename');
var runSequence = require('run-sequence');
var pump = require('pump');

gulp.task(
    'styles', function () {
        return gulp.src(
                [
                    './src/admin/scss/app.scss',
                    './src/front/scss/app.scss',
                    './src/front/scss/app-full.scss'
                ],
                {
                    base: '.'
                }
            )
            .pipe(plumber({
                errorHandler: function (err) {
                    console.log(err);
                    this.emit('end');
                }
            }))
            .pipe(sass({
                errLogToConsole: true
            }))
            .pipe(autoprefixer({
                browsers: autoPrefixBrowserList,
                cascade: true
            }))
            .on('error', gutil.log)
            .pipe(gulp.dest('.'));
    }
);

gulp.task(
    'assets-copy',
    function () {
        return gulp
            .src(
                [
                    './src/admin/**/*',
                    './src/front/**/*',
                    '!./**/scss/',
                    '!./**/*.scss',
                ],
                {
                    base: '.'
                }
            )
            .pipe(rename(function(path) {
                path.dirname = path.dirname.replace('src', 'assets');
                path.dirname = path.dirname.replace('scss', 'css');
                path.extname = path.extname === '.js' || path.extname === '.css' ? '.min' + path.extname : path.extname;
            }))
            .pipe(gulp.dest('.'));
    }
);

gulp.task(
    'styles-compress',
    function () {
        return gulp.src(
                [
                    './assets/admin/css/*.css',
                    './assets/front/css/*.css'
                ],
                {
                    base: '.'
                }
            )
            .pipe(plumber())
            .pipe(minifyCSS())
            .pipe(gulp.dest('.'));
    }
);

gulp.task(
    'scripts-compress',
    function () {
        return pump(
            [
                gulp.src(
                    [
                        './assets/admin/js/*.js',
                        './assets/front/js/*.js'
                    ],
                    {
                        base: '.'
                    }
                ),
                babel({
                    presets: ['babel-preset-es2015-script']
                }),
                uglify(),
                gulp.dest('.')
            ]
        );
    }
);

gulp.task(
    'default',
    function() {
        runSequence(
            'styles',
            'assets-copy',
            [
                'scripts-compress',
                'styles-compress'
            ]
        );
    }
);

