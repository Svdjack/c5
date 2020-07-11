const gulp = require('gulp');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify-es').default;
const minify = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const sass = require('gulp-sass');
const notify = require('gulp-notify');
const bs = require('browser-sync');
const clean = require('gulp-clean');
const rename = require('gulp-rename');

function js() {
  return gulp.src([
    'public/asset/js/*.js',
    '!public/asset/js/ads.js',
    '!public/asset/js/weird_script.js',
    '!public/asset/js/adapt.js',
  ])
    .pipe(concat('app.js'))
    .pipe(uglify())
    .pipe(gulp.dest('public/asset/app/'));
}
gulp.task('js', js);
exports.js = js;

function cssMain() {
  return gulp.src([
    'public/asset/css/reset.css',
    'public/asset/css/style.css',
    'public/asset/css/new.css',
  ])
    .pipe(concat('header_css.twig'))
    .pipe(minify())
    .pipe(gulp.dest('application/templates/common/'))
    .pipe(rename('main.css'))
    .pipe(gulp.dest('public/asset/dist/'));
}
gulp.task('cssMain', cssMain);
exports.cssMain = cssMain;


function cssRest() {
  return gulp.src([
    'public/asset/css/dropzone.css',
    'public/asset/css/colorbox.css',
    'public/asset/css/jquery.timepicker.css',
    'public/asset/css/cabinet.css',
  ])
    .pipe(concat('rest.css'))
    .pipe(minify())
    .pipe(gulp.dest('public/asset/app/'));
}
gulp.task('cssRest', cssRest);
exports.cssRest = cssRest;

function searchJs() {
  return gulp.src([
    'public/asset/search/js/*.js',
    '!public/asset/search/js/scripts.js',
  ])
    .pipe(concat('search.js'))
    .pipe(uglify().on('error', (e) => {
      console.log(e);
    }))
    .pipe(gulp.dest('public/asset/app/'));
}
gulp.task('searchJs', searchJs);
exports.searchJs = searchJs;

function searchCss() {
  return gulp.src(['public/asset/search/css/*.css'])
    .pipe(concat('search.css'))
    .pipe(minify())
    .pipe(gulp.dest('public/asset/app/'));
}
gulp.task('searchCss', searchCss);
exports.searchCss = searchCss;

function searchSite() {
  return gulp.src([
    'public/asset/search/js/cookieManager.js',
    'public/asset/search/js/searchManager.js',
    'public/asset/search/js/paramsManager.js',
    'public/asset/search/js/interfaceManager.js',
    'public/asset/search/js/front.js',
  ])
    .pipe(concat('search-site.js'))
    .pipe(uglify().on('error', (e) => {
      console.log(e);
    }))
    .pipe(gulp.dest('public/asset/app/'));
}
gulp.task('searchSite', searchSite);
exports.searchSite = searchSite;

// =========
// Sass task
// =========
function sassTask() {
  return gulp.src('public/asset/scss/new.scss')
    .pipe(sass())
    .on('error', notify.onError())
    .pipe(autoprefixer({
      cascade: false,
      flexbox: 'no-2009',
    }))
    .pipe(gulp.dest('public/asset/css'))
    .pipe(bs.stream());
}
gulp.task('sassTask', sassTask);
exports.sassTask = sassTask;

function cleanTwig() {
  return gulp.src('application/twig/cache/*', { })
    .pipe(clean());
}
gulp.task('cleanTwig', cleanTwig);
exports.cleanTwig = cleanTwig;


function cleanRoutes() {
  return gulp.src('application/route.cache', { allowEmpty: true })
    .pipe(clean());
}
gulp.task('cleanRoutes', cleanRoutes);
exports.cleanRoutes = cleanRoutes;

function angular() {
  return gulp.src([
    'public/asset/angular/*.js',
  ])
    .pipe(concat('angular.js'))
    .pipe(uglify().on('error', (e) => {
      console.log(e);
    }))
    .pipe(gulp.dest('public/asset/app/'));
}
gulp.task('angular', angular);
exports.angular = angular;

// ==========
// Watch task
// ==========
function watch(cb) {
  gulp.watch('public/asset/scss/*.scss', gulp.series(sassTask, cleanTwig));

  gulp.watch('public/asset/angular/*', gulp.series(angular));

  gulp.watch([
    'public/asset/css/*.css',
    'public/asset/search/css/*.css',
  ], gulp.series(cssMain, cssRest, searchCss, cleanTwig));

  gulp.watch([
    'public/asset/js/*.js',
    'public/asset/search/js/*.js',
  ], gulp.series(js, searchJs, searchSite, cleanTwig));

  gulp.watch('application/templates/*/*.twig', gulp.series(cleanTwig));

  gulp.watch('application/config/*.php', gulp.series(cleanTwig, cleanRoutes));

  cb();
}
gulp.task('watch', watch);

gulp.task('default',
  gulp.series(gulp.parallel(['js', 'sassTask', 'angular']),
    ['cssMain', 'cssRest', 'searchCss', 'searchJs', 'searchSite'],
    ['cleanTwig', 'cleanRoutes']));
