<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php if(settings()->aix->documents_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.documents_model.' . str_replace('-', '_', $data->plan_settings->documents_model))) ?>
            </div>

            <i class="fas fa-fw fa-sm fa-check-circle text-success"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.documents_per_month_limit'), '<strong>' . ($data->plan_settings->documents_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->documents_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->documents_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.words_per_month_limit'), '<strong>' . ($data->plan_settings->words_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->words_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->words_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->images_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.images_api.' . str_replace('-', '_', $data->plan_settings->images_api))) ?>
            </div>

            <i class="fas fa-fw fa-sm fa-check-circle text-success"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.images_per_month_limit'), '<strong>' . ($data->plan_settings->images_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->images_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->images_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->upscaled_images_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.upscaled_images_per_month_limit'), '<strong>' . ($data->plan_settings->upscaled_images_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->upscaled_images_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->upscaled_images_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->removed_background_images_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.removed_background_images_per_month_limit'), '<strong>' . ($data->plan_settings->removed_background_images_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->removed_background_images_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->removed_background_images_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->replaced_background_images_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.replaced_background_images_per_month_limit'), '<strong>' . ($data->plan_settings->replaced_background_images_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->replaced_background_images_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->replaced_background_images_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->transcriptions_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.transcriptions_per_month_limit'), '<strong>' . ($data->plan_settings->transcriptions_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->transcriptions_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->transcriptions_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>


        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.transcriptions_file_size_limit'), '<strong>' . get_formatted_bytes($data->plan_settings->transcriptions_file_size_limit * 1000 * 1000) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->transcriptions_file_size_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->chats_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.chats_per_month_limit'), '<strong>' . ($data->plan_settings->chats_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chats_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->chats_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.chat_messages_per_chat_limit'), '<strong>' . ($data->plan_settings->chat_messages_per_chat_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->chat_messages_per_chat_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->chat_messages_per_chat_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->syntheses_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.syntheses_api.' . $data->plan_settings->syntheses_api)) ?>
            </div>

            <i class="fas fa-fw fa-sm fa-check-circle text-success"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.syntheses_per_month_limit'), '<strong>' . ($data->plan_settings->syntheses_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->syntheses_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->syntheses_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.synthesized_characters_per_month_limit'), '<strong>' . ($data->plan_settings->synthesized_characters_per_month_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->synthesized_characters_per_month_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->synthesized_characters_per_month_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <?= sprintf(l('global.plan_settings.projects_limit'), '<strong>' . ($data->plan_settings->projects_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->projects_limit)) . '</strong>') ?>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->projects_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
    </div>


    <?php if(\Altum\Plugin::is_active('teams')): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.teams_limit'), '<strong>' . ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->teams_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>

        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit)) . '</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->team_members_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), '<strong>' . nr($data->plan_settings->affiliate_commission_percentage) . '%</strong>') ?>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->affiliate_commission_percentage ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        </div>
    <?php endif ?>

    <?php if(settings()->main->api_is_enabled): ?>
        <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->api_is_enabled ? null : 'text-muted' ?>">
            <div>
                <?= l('global.plan_settings.api_is_enabled') ?>
                <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.api_is_enabled_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
            </div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->api_is_enabled ? 'fa-check-circle text-success' : 'fa-times-circle' ?>"></i>
        </div>
    <?php endif ?>

    <div class="d-flex justify-content-between align-items-center my-3 <?= $data->plan_settings->no_ads ? null : 'text-muted' ?>">
        <div>
            <?= l('global.plan_settings.no_ads') ?>
            <span class="mr-1" data-toggle="tooltip" title="<?= l('global.plan_settings.no_ads_help') ?>"><i class="fas fa-fw fa-xs fa-circle-question text-gray-500"></i></span>
        </div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->no_ads ? 'fa-check-circle text-success' : 'fa-times-circle' ?>"></i>
    </div>
</div>
