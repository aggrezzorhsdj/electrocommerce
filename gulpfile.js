const sass = require('gulp-sass')(require('sass'));
const babel = require('gulp-babel');
const sourcemaps = require('gulp-sourcemaps');
const clean = require('gulp-clean');
const browserify = require('browserify');
const source = require('vinyl-source-stream');
const babelify = require('babelify');

const {src, dest, watch, parallel, series} = require('gulp');

function CleanDist(cb) {
    src('dist/*', {read: false})
        .pipe(clean());
    cb();
}

function DevStyles(cb) {
    src('src/scss/base.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(sourcemaps.write())
        .pipe(dest('dist/'));
    cb();
}

const b = browserify('./src/js/main.js');

function DevScripts(cb) {

    browserify('./src/js/main.js')
        .transform(babelify.configure({
            presets: ["@babel/preset-env"]
        }))
        .bundle()
        .pipe(source('main.js'))
        .pipe(dest('dist/'));
    cb();
}

exports.default = DevStyles;
exports.build = parallel(CleanDist, DevStyles, DevScripts);
exports.watch = function() {
    watch('scss/**/*.scss', DevStyles);
}