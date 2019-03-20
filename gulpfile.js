'use strict';

const gulp = require('gulp');
const gulpif = require('gulp-if');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const sourcemaps = require('gulp-sourcemaps');
const minimist = require('minimist');
const webpack = require('webpack-stream');
const del = require('del');
const mergeStream = require('merge-stream');
const named = require('vinyl-named')

/**
 * Command line arguments.
 *
 * @type {{prod: boolean}}
 */
const options = minimist(process.argv.slice(2), {boolean: 'prod'});

const ASSETS_PATH = './assets';
const DIST_PATH = './public/dist';

/***********************************************************************************************************************
 * Clear
 **********************************************************************************************************************/

gulp.task('js:clear', function () {
  return del([`${DIST_PATH}/js/**`]);
});

gulp.task('sass:clear', function () {
  return del([`${DIST_PATH}/css/**`]);
});

gulp.task('images:clear', function () {
  return del([`${DIST_PATH}/images/**`]);
});

/***********************************************************************************************************************
 * Build
 **********************************************************************************************************************/

gulp.task('js', function () {
  return gulp.src(`${ASSETS_PATH}/js/*.js`)
    .pipe(named())
    .pipe(webpack())
    .pipe(gulp.dest(`${DIST_PATH}/js`));
});

gulp.task('sass', function () {
  const debug = !options.prod;
  const stream = gulp.src(`${ASSETS_PATH}/sass/*.scss`);

  return mergeStream(
    stream
      .pipe(gulpif(debug, sourcemaps.init()))
      .pipe(sass({outputStyle: 'expanded'}).on('error', sass.logError))
      .pipe(gulpif(debug, sourcemaps.write('/'))),
    stream
      .pipe(gulpif(debug, sourcemaps.init()))
      .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
      .pipe(rename({suffix: '.min'}))
      .pipe(gulpif(debug, sourcemaps.write('/')))
  )
    .pipe(gulp.dest(`${DIST_PATH}/css`));
});

gulp.task('images', function () {
  return gulp
    .src([`${ASSETS_PATH}/images/**/*`])
    .pipe(gulp.dest(`${DIST_PATH}/images`));
});

/***********************************************************************************************************************
 * Watch
 **********************************************************************************************************************/

gulp.task('js:watch', function () {
  return gulp.watch([
    `${ASSETS_PATH}/js/**/*.js`
  ], gulp.series('js'));
});

gulp.task('sass:watch', function () {
  return gulp.watch([
    `${ASSETS_PATH}/sass/**/*.scss`
  ], gulp.series('sass'));
});

gulp.task('watch', gulp.series(
  'default',

  gulp.parallel(
    'js:watch',
    'sass:watch',
  )
));

/***********************************************************************************************************************
 * Default
 **********************************************************************************************************************/

gulp.task('default', gulp.parallel(
  gulp.series(
    gulp.parallel(
      'js:clear',
      'sass:clear',
      'images:clear',
    ),

    gulp.parallel(
      'js',
      'sass',
      'images',
    ),
  ),
));
