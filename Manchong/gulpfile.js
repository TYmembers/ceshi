var gulp = require('gulp'),
    less = require('gulp-less'),
    watch = require('gulp-watch'),
    imageisux = require('gulp-imageisux');

gulp.task('lessIn',function(){
    gulp.src('less/less.less')
        .pipe(less())
        .pipe(gulp.dest('css'))
})

gulp.task('img',function(){
    gulp.src('./img/*')
        .pipe(imageisux('./image',true))
})

gulp.task('watchLess',function(){
    gulp.watch('less/*.less',['lessIn'])
})