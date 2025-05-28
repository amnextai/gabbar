<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

return [
    /* Main */
    'logo_light' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'path' => 'main/',
    ],
    'logo_dark' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'path' => 'main/',
    ],
    'logo_email' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif'],
        'path' => 'main/',
    ],
    'favicon' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'ico', 'svg', 'gif', 'webp'],
        'path' => 'main/',
    ],
    'opengraph' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'path' => 'main/',
    ],

    /* PWA plugin */
    'app_icon' => [
        'whitelisted_file_extensions' => ['png'],
        'path' => 'pwa/',
    ],
    'app_screenshots' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png'],
        'path' => 'pwa/',
    ],
    'pwa' => [
        'path' => 'pwa/',
    ],

    'push_notifications_icon' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png'],
        'path' => 'main/',
    ],

    /* Blog featured images */
    'blog' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'path' => 'blog/',
    ],

    /* Payment proofs for offline payments */
    'offline_payment_proofs' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'pdf'],
        'path' => 'offline_payment_proofs/',
    ],

    /* AIX */
    'images' => [
        'whitelisted_file_extensions' => ['png'],
        'path' => 'images/',
    ],

    'upscaled_images' => [
        'whitelisted_file_extensions' => ['png', 'jpg', 'jpeg'],
        'path' => 'upscaled_images/',
    ],

    'removed_background_images' => [
        'whitelisted_file_extensions' => ['png', 'jpg', 'jpeg'],
        'path' => 'removed_background_images/',
    ],

    'replaced_background_images' => [
        'whitelisted_file_extensions' => ['png', 'jpg', 'jpeg'],
        'path' => 'replaced_background_images/',
    ],

    'transcriptions' => [
        'whitelisted_file_extensions' => ['mp3', 'mp4', 'mpeg', 'mpga', 'm4a', 'wav', 'webm'],
        'path' => 'cache/',
    ],

    'chats_assistants' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'path' => 'chats_assistants/',
    ],

    'chats_images' => [
        'whitelisted_file_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'path' => 'chats_images/',
    ],

    'syntheses' => [
        'whitelisted_file_extensions' => ['mp3'],
        'path' => 'syntheses/',
    ],
];
