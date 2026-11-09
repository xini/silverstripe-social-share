import { mkdir, readdir, readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';
import * as sass from 'sass';
import postcss from 'postcss';
import postcssImport from 'postcss-import';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';
import { optimize } from 'svgo';
import { watch } from 'chokidar';

const root = path.dirname(fileURLToPath(import.meta.url));
const output = path.resolve(root, process.env.BUILD_DIR || 'dist');
const args = new Set(process.argv.slice(2));
const buildCss = args.has('--css') || (!args.has('--svgs') && !args.has('--svg') && !args.has('--js'));
const buildSvgs = args.has('--svgs') || args.has('--svg') || (!args.has('--css') && !args.has('--js'));

async function styles() {
    const source = path.join(root, 'src/scss');
    const destination = path.join(output, 'css');
    await mkdir(destination, { recursive: true });
    for (const file of await readdir(source)) {
        if (!file.endsWith('.scss') || file.startsWith('_')) continue;
        const input = path.join(source, file);
        const target = path.join(destination, file.replace(/\.scss$/, '.css'));
        const result = await sass.compileAsync(input, { style: 'compressed', sourceMap: true });
        const css = await postcss([postcssImport(), autoprefixer({ cascade: false, supports: false }), cssnano()])
            .process(result.css, { from: input, to: target, map: { prev: result.sourceMap, inline: false } });
        await writeFile(target, css.css);
        await writeFile(`${target}.map`, css.map.toString());
    }
}

async function svgs() {
    const source = path.join(root, 'src/icons');
    const destination = path.join(output, 'icons');
    await mkdir(destination, { recursive: true });
    for (const file of await readdir(source)) {
        if (!file.endsWith('.svg')) continue;
        const input = path.join(source, file);
        const target = path.join(destination, file);
        const content = await readFile(input, 'utf8');
        const result = optimize(content, {
            path: input,
            plugins: [
                {
                    name: 'preset-default',
                    params: {
                        overrides: {
                            cleanupIds: false,
                            removeUselessStrokeAndFill: false,
                            removeUselessDefs: false,
                        },
                    },
                },
            ],
        });
        await writeFile(target, result.data);
    }
}

async function build() {
    if (buildCss) await styles();
    if (buildSvgs) await svgs();
}

await build();
if (args.has('--watch')) {
    let pending = Promise.resolve();
    watch(path.join(root, 'src'), { ignoreInitial: true }).on('all', () => {
        pending = pending.then(build).catch(error => console.error(error));
    });
    console.log('Watching client sources for changes');
}
