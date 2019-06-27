var // Package Variables
    dotenv = require('dotenv').config({path: '.env'}),
    gulp = require('gulp'),
    sass = require('gulp-sass'),
    uglify = require('gulp-uglify'),
    autoprefixer = require('gulp-autoprefixer'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    plumber = require('gulp-plumber'),
    notify = require('gulp-notify'),
    insert = require('gulp-insert'),
    // Environment Variables
    srcPath = process.env.SRC_PATH,
    assetPath = process.env.ASSET_PATH,
    styleName = process.env.STYLE_NAME,
    scriptName = process.env.SCRIPT_NAME;

gulp.task('default', ['sass', 'scripts', 'watch']);

gulp.task('build', ['sass', 'scripts']);

// Compiles both unminified and minified CSS files
gulp.task('sass', function () {
  gulp.src(srcPath + assetPath + '/styles/' + styleName + '.scss')
    .pipe(plumber())
    .pipe(sass({
      outputStyle: 'expanded',
      includePaths: ['node_modules']
    }))
    .on('error', onError)
    .pipe(autoprefixer({
      browsers: ['last 100 versions'],
      cascade: false
    }))
    .on('error', function (err) {
      console.log(err.message);
    })
    .pipe(sass({
      outputStyle: 'compressed'
    }))
    .pipe(rename(styleName + '.min.css'))
    .pipe(gulp.dest(srcPath + assetPath + '/styles/min'));
});

// Compiles both unminified and minified JS files
gulp.task('scripts', function() {
  return gulp.src(srcPath + assetPath + '/scripts/' + '*.js')
    .pipe(plumber())
    .pipe(concat(scriptName + '.js'))
    .pipe(insert.wrap('(function($){\n\n', '\n\n})(jQuery);'))
    .on('error', onError)
    .pipe(rename(scriptName + '.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest(srcPath + assetPath + '/scripts/min'));
});

// Watches files for changes and compiles on the fly
gulp.task('watch', function () {
  gulp.watch(srcPath + assetPath + '/styles/' + '**/*.scss', ['sass']);
  gulp.watch(srcPath + assetPath + '/scripts/' + '*.js', ['scripts']);
});

// error notifications
var onError = function (err) {
  notify({
    title: 'Gulp Task Error',
    message:  "Error: <%= error.message %>",
  }).write(err);

  this.emit('end');
}
