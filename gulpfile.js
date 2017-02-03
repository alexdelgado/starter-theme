var fs = require('fs'),
    es = require('event-stream'),
    gulp = require('gulp'),
    compass = require('gulp-compass'),
    concat = require('gulp-concat'),
    jshint = require('gulp-jshint'),
    scsslint = require('gulp-scsslint'),
    sourcemaps = require('gulp-sourcemaps'),
    uglify = require('gulp-uglify');

/**
 * JavaScript Tasks
 *
 * 'javascript': concatenate, only
 * 'uglify': concatenate, minify, and generate sourcemap
 */
gulp.task('javascript', ['jshint'], function() {

    var theme = [
        'js/vendor/*.js',
        'js/vendor/**/*.js',
        'js/src/*.js',
        'js/src/**/*.js',
        '!js/src/wp-admin/*.js',
    ];

    var admin = ['js/src/wp-admin.js'];

    return es.merge(
        gulp.src(theme)
            .pipe(concat('theme.min.js'))
            .pipe(gulp.dest('js')),
        gulp.src(admin)
            .pipe(concat('wp-admin.min.js'))
            .pipe(gulp.dest('js'))
    );
});

gulp.task('uglify', ['jshint'], function() {

    var theme = [
        'js/vendor/*.js',
        'js/vendor/**/*.js',
        'js/src/*.js',
        'js/src/**/*.js',
        '!js/src/wp-admin/*.js',
    ];

    var admin = ['js/src/wp-admin.js'];

    return es.merge(
        gulp.src(theme)
            .pipe(sourcemaps.init())
            .pipe(concat('theme.min.js'))
            .pipe(uglify())
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest('js')),
        gulp.src(admin)
            .pipe(sourcemaps.init())
            .pipe(concat('wp-admin.min.js'))
            .pipe(uglify())
            .pipe(sourcemaps.write('./'))
            .pipe(gulp.dest('js'))
    );
});

/**
 * Linting Tasks
 *
 * 'jshint': javascript Linting
 * 'scsslint': sass linting
 */
gulp.task('jshint', function() {

    var assets = ['js/src/*.js', 'js/src/**/*.js'];

    return gulp.src(assets)
        .pipe(jshint())
        .pipe(jshint.reporter('default', {verbose: true}));
});

gulp.task('scsslint', function() {

    var assets = [
        './scss/**/*.scss',
        '!scss/vendor/*.scss',
        '!scss/vendor/**/*.scss',
    ];

    return gulp.src(assets)
        .pipe(scsslint())
        .pipe(scsslint.reporter());
});

/**
 * Sass Compilation
 *
 * 'compass': concatenate, minify and generate sourcemap
 * 'sass': concatenate only
 */
gulp.task('compass', ['scsslint'], function() {

    var assets = [
        './scss/**/*.scss',
        '!scss/vendor/*.scss',
        '!scss/vendor/**/*.scss',
    ];

    return gulp.src(assets)
        .pipe(compass({
            config_file: 'config.rb',
            css: 'css',
            sass: 'scss',
            sourcemap: true
        }));
});

gulp.task('sass', ['scsslint'], function() {

    var assets = [
        './scss/**/*.scss',
        '!scss/vendor/*.scss',
        '!scss/vendor/**/*.scss',
    ];

    return gulp.src(assets)
        .pipe(compass({
            css: 'css',
            sass: 'scss',
            style: 'nested'
        }));
});

/**
 * Watch Files
 */
gulp.task('watch', ['build'], function() {
    gulp.watch(['scss/*.scss', 'scss/**/*.scss'], ['compass']);
    gulp.watch(['js/src/*.js', 'js/src/**/*.js', 'js/vendor/*.js', 'js/vendor/**/*.js'], ['uglify']);
});

gulp.task('watch-debug', ['build-debug'], function() {
    gulp.watch(['scss/*.scss', 'scss/**/*.scss'], ['sass']);
    gulp.watch(['js/src/*.js', 'js/src/**/*.js', 'js/vendor/*.js', 'js/vendor/**/*.js'], ['javascript']);
});

/**
 * Task Bundles
 */
gulp.task('build', [
    'compass',
    'uglify'
]);

gulp.task('build-debug', [
    'sass',
    'javascript'
]);

gulp.task('default', ['build', 'watch']);
