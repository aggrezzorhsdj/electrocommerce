const svgSprite = require("gulp-svg-sprites"),
    svgMin = require("gulp-svgmin"),
    rename = require("gulp-rename"),
    inject = require("gulp-inject"),
    path = require("path");

const { src, dest } = require("gulp");

function svg() {
    const svgIcons = src("./assets/images/icons/*.svg")
        .pipe(svgMin(function getOptions(file) {
            const prefix = path.basename(
                file.relative,
                path.extname(file.relative)
            );
            return {
                plugins: [
                    {
                        name: "cleanupIDs",
                        params: {
                            prefix: prefix + "-",
                            minify: true,
                        },
                    },
                    {
                        name: "removeAttrs",
                        params: {
                            attrs: "(fill|stroke)"
                        }
                    }
                ]
            };
        }))
        .pipe(rename({ prefix: "icon-" }))
        .pipe(svgSprite({ mode: "symbols" }));

    function fileContents(filePath, file) {
        return file.contents.toString()
            .replace(/(\r\n|\n|\r)/gm, "")
            .replace(/>\s*/g, ">")
            .replace(/\s*</g, "<");
    }

    return src("./sprites.php")
        .pipe(inject(svgIcons, { transform: fileContents }))
        .pipe(dest("./"));
}

exports.svg = svg;
