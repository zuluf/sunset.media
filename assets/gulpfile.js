var gulp = require('gulp'),
	fs = require('fs'),
	uglify = require('gulp-uglifyjs'),
	less = require('gulp-less'),
	rename = require('gulp-rename'),
	minifyCss = require('gulp-minify-css'),
	task = (process.argv[2] || null);

gulp.task('uglify', function() {
	return gulp.src(['js/libs/**/*.js', 'js/sunset/**/*.js', 'js/app/**/*.js', 'js/app.js'])
		.pipe(uglify('app.min.js'))
		.pipe(gulp.dest('dist'));
});

gulp.task('less', function() {
	return gulp.src(['less/main.less'])
		.pipe(less())
		.pipe(minifyCss())
		.pipe(rename('app.min.css'))
		.pipe(gulp.dest('dist'));
});

gulp.task('version', function(callback) {
	var date = new Date(),
		expires = new Date();

	expires.setMonth(date.getMonth() + 11)

	var	content =
			'<IfModule mod_headers.c>\n' +
				'\t<FilesMatch "\\.(bmp|css|flv|gif|ico|jpg|jpeg|js|pdf|png|svg|swf|tif|tiff)$">\n' +
					'\t\tHeader unset ETag\n' +
					'\t\tHeader set Last-Modified "' + date.toUTCString() + '"\n' +
					'\t\tHeader set Cache-Control "public, max-age=31536000"\n' +
					'\t\tHeader set Expires "' + expires.toUTCString() + '"\n' +
				'\t</FilesMatch>\n' +
			'</IfModule>\n' +
			'FileETag None\n';

  return fs.writeFile('.htaccess', content, callback);
});

gulp.task('live', function() {
	return gulp.watch('less/**/*.less', ['less']);
});

gulp.task('build', ['uglify', 'less', 'version']);
gulp.task('dev', ['uglify', 'less']);
gulp.task('default', ['build']);

if (task && !gulp.hasTask(task)) {
	throw "Gulp task '" + task + "' not registered";
}