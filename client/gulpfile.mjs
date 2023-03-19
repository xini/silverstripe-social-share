import gulp from 'gulp';
const { series, parallel, src, dest, task, watch } = gulp;
import autoprefixer from 'gulp-autoprefixer';
import changed from 'gulp-changed';
import cssimport from 'gulp-cssimport';
import cleancss from 'gulp-clean-css';
import { deleteSync } from 'del';
import imagemin from 'gulp-imagemin';
import imageminSvgo from 'imagemin-svgo';
import plumber from 'gulp-plumber';
import dartSass from 'sass';
import gulpSass from 'gulp-sass';
const sass = gulpSass(dartSass);
import sourcemaps from 'gulp-sourcemaps';
import through2 from 'through2';

//load paths
const paths = {
    "styles": {
        "src": "src/scss",
        "filter": "/**/*.+(scss)",
        "dist": "dist/css",
        "distfilter": "/**/*.+(css|map)"
    },
    "svgs": {
        "src": "src/icons",
        "filter": "/**/*.+(svg)",
        "dist": "dist/icons"
    }
}

const sassOptions = {
	errLogToConsole: true,
	outputStyle: 'compressed'
};

const autoprefixerOptions = {
	cascade: false,
	supports: false
};

function styles(cb) {
    src(paths.styles.src + paths.styles.filter)
        .pipe(plumber({
            errorHandler: onError
        }))
        .pipe(sourcemaps.init())
        .pipe(cssimport({matchPattern: "*.css"}))
        .pipe(sass(sassOptions).on('error', sass.logError))
        .pipe(autoprefixer(autoprefixerOptions))
        .pipe(cleancss({
            level: {
                1: {
                    all: true,
                    normalizeUrls: false
                },
                2: {
                    restructureRules: true
                }
            }
        }))
        .pipe(sourcemaps.write('.', {
            sourceMappingURLPrefix: paths.themedir + '/' + paths.styles.dist
        }))
        .pipe( through2.obj( function( file, enc, cb ) {
            var date = new Date();
            file.stat.atime = date;
            file.stat.mtime = date;
            cb( null, file );
        }))
        .pipe(dest(paths.styles.dist));
    cb();
}

function svgs(cb) {
	src(paths.svgs.src + paths.svgs.filter)
	    .pipe(plumber({
	        errorHandler: onError
	    }))
	    .pipe(changed(paths.svgs.dist))
	    .pipe(imagemin(
	        [
                imageminSvgo({
	                plugins: [
	                    {name: 'removeViewBox', active: false},
	                    {name: 'removeUselessStrokeAndFill', active: false},
	                    {name: 'cleanupIDs', active: false},
	                    {name: 'removeUselessDefs', active: false}
	                ]
	            })
	        ],
	        {
	            verbose: true
	        }
	    ))
	    .pipe(dest(paths.svgs.dist));
	cb();
}
function cleanstyles(cb) {
    deleteSync([
        paths.styles.dist + paths.styles.distfilter
	]);
	cb();
}

function cleansvgs(cb) {
    deleteSync([
		paths.svgs.dist + paths.svgs.filter
	]);
	cb();
}
function watchAll() {
	// watch for style changes
	watch(paths.styles.src + paths.styles.filter, series(cleanstyles, styles));
	// watch for svg changes
	watch(paths.svgs.src + paths.svgs.filter, series(cleansvgs, svgs));
}

function onError(err) {
    console.log(err);
}

task('clean', series(
	parallel(
        cleanstyles,
		cleansvgs
	)
));

task('build', series(
	parallel(
        cleanstyles,
        cleansvgs
	),
	parallel(
		styles,
		svgs
	)
));

task('css', series(
	cleanstyles,
	styles
));

task('svgs', series(
    cleansvgs,
    svgs
));

task('default', series(
    parallel(
        cleanstyles,
        cleansvgs
    ),
    parallel(
        styles,
        svgs
    ),
	watchAll
));
