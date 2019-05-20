const gulp = require('gulp');
const pump = require('pump');

const sass = require('gulp-sass');
const $sass = () => sass().on('error', sass.logError);

const sassFiles = '**/*.scss';

const glamSrc = './';
const themeSrc = '../../theme/';
const glamCssSrc = glamSrc + 'css/';

const glamSassFiles = glamCssSrc + sassFiles;
const themeSassFiles = themeSrc + sassFiles;

console.log(themeSassFiles);

gulp.task('glam-sass', done => {
	pump(
		gulp.src(glamSassFiles),
		$sass(),
		gulp.dest(glamCssSrc),
		done
	);
});

gulp.task('theme-sass', done => {
	pump(
		gulp.src(themeSassFiles),
		$sass(),
		gulp.dest(themeSrc),
		done
	);
});

gulp.task('css-watch', done => {
	gulp.watch(glamSassFiles, gulp.series('glam-sass'));
	gulp.watch(themeSassFiles, gulp.series('theme-sass'));
	done();
});

gulp.task('css', gulp.series(
	'glam-sass',
	'theme-sass',
	'css-watch'
));

gulp.task('default', gulp.parallel(
	'css'
));