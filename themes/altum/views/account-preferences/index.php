<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="d-flex align-items-center mb-3">
        <h1 class="h4 m-0"><?= l('account_preferences.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('account_preferences.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="openai_api_key"><?= l('account_preferences.input.aix.openai_api_key') ?></label>
                    <textarea id="openai_api_key" name="openai_api_key" class="form-control"><?= $this->user->preferences->openai_api_key ?></textarea>
                    <small class="form-text text-muted"><?= l('account_preferences.input.aix.openai_api_key_help') ?></small>
                    <?php if($this->user->plan_settings->exclusive_personal_api_keys): ?>
                        <small class="form-text text-muted"><?= l('account_preferences.input.aix.required_help') ?></small>
                    <?php else: ?>
                        <small class="form-text text-muted"><?= l('account_preferences.input.aix.optional_help') ?></small>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label for="clipdrop_api_key"><?= l('account_preferences.input.aix.clipdrop_api_key') ?></label>
                    <textarea id="clipdrop_api_key" name="clipdrop_api_key" class="form-control"><?= $this->user->preferences->clipdrop_api_key ?></textarea>
                    <small class="form-text text-muted"><?= l('account_preferences.input.aix.clipdrop_api_key_help') ?></small>
                    <?php if($this->user->plan_settings->exclusive_personal_api_keys): ?>
                        <small class="form-text text-muted"><?= l('account_preferences.input.aix.required_help') ?></small>
                    <?php else: ?>
                        <small class="form-text text-muted"><?= l('account_preferences.input.aix.optional_help') ?></small>
                    <?php endif ?>
                </div>

                <div class="form-group">
                    <label for="aws_access_key"><?= l('account_preferences.input.aws_access_key') ?></label>
                    <input id="aws_access_key" type="text" name="aws_access_key" class="form-control" value="<?= $this->user->preferences->aws_access_key ?>" />
                </div>

                <div class="form-group">
                    <label for="aws_secret_access_key"><?= l('account_preferences.input.aws_secret_access_key') ?></label>
                    <input id="aws_secret_access_key" type="text" name="aws_secret_access_key" class="form-control" value="<?= $this->user->preferences->aws_secret_access_key ?>" />
                </div>

                <div class="form-group">
                    <label for="aws_region"><?= l('account_preferences.input.aws_region') ?></label>
                    <select id="aws_region" name="aws_region" class="custom-select">
                        <?php foreach(require APP_PATH . 'includes/aix/ai_syntheses_aws_polly_regions.php' as $region_key => $region_name): ?>
                            <option value="<?= $region_key ?>" <?= $this->user->preferences->aws_region == $region_key ? 'selected="selected"' : null ?>><?= $region_name . ' - ' . $region_key ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="default_results_per_page"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('account_preferences.input.default_results_per_page') ?></label>
                    <select id="default_results_per_page" name="default_results_per_page" class="custom-select <?= \Altum\Alerts::has_field_errors('default_results_per_page') ? 'is-invalid' : null ?>">
                        <?php foreach([10, 25, 50, 100, 250, 500, 1000] as $key): ?>
                            <option value="<?= $key ?>" <?= ($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page) == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= \Altum\Alerts::output_field_error('default_results_per_page') ?>
                </div>

                <div class="form-group">
                    <label for="default_order_type"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('account_preferences.input.default_order_type') ?></label>
                    <select id="default_order_type" name="default_order_type" class="custom-select <?= \Altum\Alerts::has_field_errors('default_order_type') ? 'is-invalid' : null ?>">
                        <option value="ASC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                        <option value="DESC" <?= ($this->user->preferences->default_order_type ?? settings()->main->default_order_type) == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                    </select>
                    <?= \Altum\Alerts::output_field_error('default_order_type') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>
        </div>
    </div>
</div>
