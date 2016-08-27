'use strict';

var gulp = require('gulp');
var sourcemaps = require('gulp-sourcemaps');
var sass = require('gulp-sass');
var cssNano = require('gulp-cssnano');
var uglify = require('gulp-uglify');

gulp.task('styles', function() {
	gulp.src('./assets/styles/*.scss')
    .pipe(sourcemaps.init())
      .pipe(sass())
      .pipe(cssNano({'safe': true}))
    .pipe(sourcemaps.write())
		.pipe(gulp.dest('./dist/styles/'));
});

gulp.task('scripts', function() {
	gulp.src('./assets/scripts/*.js')
    .pipe(sourcemaps.init())
      .pipe(uglify())
    .pipe(sourcemaps.write())
		.pipe(gulp.dest('./dist/scripts/'));
});

gulp.task('default', function() {
  gulp.start('styles');
  gulp.start('scripts');
});
