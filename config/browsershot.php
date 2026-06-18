<?php

return [

    /*
    |----------------------------------------------------------------------
    | Headless browser binaries used by Spatie\Browsershot
    |----------------------------------------------------------------------
    | Server-side CV rendering spawns a headless Chromium instance via
    | Puppeteer. Override these paths per-environment so the export
    | pipeline never depends on the developer machine's defaults.
    */

    'chrome_path' => env('CHROME_PATH', '/usr/bin/chromium'),

    'node_path' => env('NODE_PATH', '/usr/bin/node'),

    /*
    | The Node binary Puppeteer ships with; falls back to the package's
    | own bundled binary when null.
    */
    'npm_package_path' => env('BROWSERSHOT_NPM_PACKAGE_DIR', base_path('node_modules')),

];
