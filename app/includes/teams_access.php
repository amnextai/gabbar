<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$access = [
    'read' => [
        'read.all' => l('global.all')
    ],

    'create' => [
        'create.projects' => l('projects.title'),
    ],

    'update' => [
        'update.projects' => l('projects.title'),
    ],

    'delete' => [
        'delete.projects' => l('projects.title'),
    ],
];

if(settings()->aix->documents_is_enabled) {
    $access['create']['create.documents'] = l('documents.title');
    $access['update']['update.documents'] = l('documents.title');
    $access['delete']['delete.documents'] = l('documents.title');
}

if(settings()->aix->images_is_enabled) {
    $access['create']['create.images'] = l('images.title');
    $access['update']['update.images'] = l('images.title');
    $access['delete']['delete.images'] = l('images.title');
}

if(settings()->aix->upscaled_images_is_enabled) {
    $access['create']['create.upscaled_images'] = l('upscaled_images.title');
    $access['update']['update.upscaled_images'] = l('upscaled_images.title');
    $access['delete']['delete.upscaled_images'] = l('upscaled_images.title');
}

if(settings()->aix->removed_background_images_is_enabled) {
    $access['create']['create.removed_background_images'] = l('removed_background_images.title');
    $access['update']['update.removed_background_images'] = l('removed_background_images.title');
    $access['delete']['delete.removed_background_images'] = l('removed_background_images.title');
}

if(settings()->aix->replaced_background_images_is_enabled) {
    $access['create']['create.replaced_background_images'] = l('replaced_background_images.title');
    $access['update']['update.replaced_background_images'] = l('replaced_background_images.title');
    $access['delete']['delete.replaced_background_images'] = l('replaced_background_images.title');
}

if(settings()->aix->transcriptions_is_enabled) {
    $access['create']['create.transcriptions'] = l('transcriptions.title');
    $access['update']['update.transcriptions'] = l('transcriptions.title');
    $access['delete']['delete.transcriptions'] = l('transcriptions.title');
}

if(settings()->aix->chats_is_enabled) {
    $access['create']['create.chats'] = l('chats.title');
    $access['update']['update.chats'] = l('chats.title');
    $access['delete']['delete.chats'] = l('chats.title');
}

if(settings()->aix->syntheses_is_enabled) {
    $access['create']['create.syntheses'] = l('syntheses.title');
    $access['update']['update.syntheses'] = l('syntheses.title');
    $access['delete']['delete.syntheses'] = l('syntheses.title');
}

return $access;
