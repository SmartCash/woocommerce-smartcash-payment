var gulp         = require("gulp"),
    wpPot        = require('gulp-wp-pot');
    potomo       = require('gulp-potomo');


gulp.task('potgenerator', function () {
  return gulp.src('./**/**/*.php')
    .pipe(wpPot( {
        domain: 'wcscp',
        package: 'WooCommerce_SmartCash'
    } ))
    .pipe(gulp.dest('./languages/wcscp.po'));
});


gulp.task('potomo', function () {
  return gulp.src('./languages/*.po')
    .pipe(potomo({poDel: false}))
    .pipe(gulp.dest('./languages'));
});
