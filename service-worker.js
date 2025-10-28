'use strict';
importScripts('/local/templates/bitlate_pro/js/sw-toolbox.js');
toolbox.precache([
    "/local/templates/bitlate_pro/themes/black_blue/css/main.css",
    "/local/templates/bitlate_pro/themes/black_brown/css/main.css",
    "/local/templates/bitlate_pro/themes/blue_green/css/main.css",
    "/local/templates/bitlate_pro/themes/blue_orange/css/main.css",
    "/local/templates/bitlate_pro/themes/blue_red/css/main.css",
    "/local/templates/bitlate_pro/themes/blue_yellow/css/main.css",
    "/local/templates/bitlate_pro/themes/green_red/css/main.css",
]);
toolbox.router.get('/local/templates/bitlate_pro/images/*', toolbox.cacheFirst);
toolbox.router.get('/local/templates/bitlate_pro/fonts/*', toolbox.cacheFirst);
toolbox.router.get('/upload/*', toolbox.cacheFirst);
toolbox.router.get('*', toolbox.networkFirst, { networkTimeoutSeconds: 5});