<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <h1 class="h4 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-table-cells mr-1"></i> <?= l('dashboard.header') ?></h1>
        </div>
    </div>

    <?php if(settings()->aix->documents_is_enabled): ?>
        <div class="my-4">
            <div class="row m-n2">
                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <a href="<?= url('documents') ?>" class="stretched-link">
                                <i class="fas fa-fw fa-robot text-primary-600"></i>
                            </a>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('documents.widget.total'), '<span class="h6">' . nr($data->total_documents) . '</span>') ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" title="<?= l('documents.widget.this_month') ?>">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <i class="fas fa-fw fa-keyboard text-primary-600"></i>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('documents.widget.words_current_month'), '<span class="h6">' . nr($data->words_current_month) . '</span>') ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" title="<?= l('documents.widget.this_month') ?>">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <i class="fas fa-fw fa-feather text-primary-600"></i>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('documents.widget.available_words'), '<span class="h6">' . ($this->user->plan_settings->words_per_month_limit != -1 ? nr($data->available_words) : l('global.unlimited')) . '</span>') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-4">
            <div class="d-flex align-items-center mb-3">
                <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-robot mr-1"></i> <?= l('dashboard.documents_header') ?></h2>

                <div class="flex-fill">
                    <hr class="border-gray-100" />
                </div>

                <div class="ml-3">
                    <a href="<?= url('document-create') ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->documents_current_month, $this->user->plan_settings->documents_per_month_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('documents.create') ?>
                    </a>
                </div>
            </div>

            <?php if(count($data->documents)): ?>
                <div class="table-responsive table-custom-container">
                    <table class="table table-custom">
                        <thead>
                        <tr>
                            <th><?= l('documents.table.document') ?></th>
                            <th><?= l('global.type') ?></th>
                            <th><?= l('documents.words') ?> <span data-toggle="tooltip" title="<?= l('documents.words_help') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-muted"></i></span></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($data->documents as $row): ?>

                            <tr>
                                <td class="text-nowrap">
                                    <div class="d-flex flex-column">
                                        <div><a href="<?= url('document-update/' . $row->document_id) ?>"><?= $row->name ?></a></div>
                                    </div>
                                </td>

                                <td class="text-nowrap">
                                    <a href="<?= url('document-create?type=' . $row->type) ?>" class="px-2 py-1 rounded small font-weight-bold text-decoration-none" style="background: <?= $data->templates_categories[$row->template_category_id]->background ?>; color: <?= $data->templates_categories[$row->template_category_id]->color ?>;">
                                        <i class="<?= $data->templates[$row->type]->icon ?> fa-fw"></i> <?= $data->templates[$row->type]->settings->translations->{\Altum\Language::$name}->name ?>
                                    </a>
                                </td>

                                <td class="text-nowrap">
                                    <span class="text-muted"><?= nr($row->words) ?></span>
                                </td>

                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                    <i class="fas fa-fw fa-clock text-muted"></i>
                                </span>

                                        <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_datetime) . ')</small>' : '<br />-')) ?>">
                                    <i class="fas fa-fw fa-history text-muted"></i>
                                </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end">
                                        <?= include_view(THEME_PATH . 'views/documents/document_dropdown_button.php', ['id' => $row->document_id, 'resource_name' => $row->name]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td colspan="5">
                                <a href="<?= url('documents') ?>" class="text-muted">
                                    <i class="fas fa-angle-right fa-sm fa-fw mr-1"></i> <?= l('global.view_more') ?>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            <?php else: ?>

                <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                    'filters_get' => $data->filters->get ?? [],
                    'name' => 'documents',
                    'has_secondary_text' => true,
                ]); ?>

            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->images_is_enabled): ?>
        <div class="mt-6 mb-4">
            <div class="row m-n2">
                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <a href="<?= url('images') ?>" class="stretched-link">
                                <i class="fas fa-fw fa-icons text-primary-600"></i>
                            </a>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('images.widget.total'), '<span class="h6">' . nr($data->total_images) . '</span>') ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" title="<?= l('images.widget.this_month') ?>">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <i class="fas fa-fw fa-images text-primary-600"></i>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('images.widget.images_current_month'), '<span class="h6">' . nr($data->images_current_month) . '</span>') ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" title="<?= l('images.widget.this_month') ?>">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <i class="fas fa-fw fa-camera-retro text-primary-600"></i>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('images.widget.available_images'), '<span class="h6">' . ($this->user->plan_settings->images_per_month_limit != -1 ? nr($data->available_images) : l('global.unlimited')) . '</span>') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-4">
            <div class="d-flex align-items-center mb-3">
                <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-robot mr-1"></i> <?= l('dashboard.images_header') ?></h2>

                <div class="flex-fill">
                    <hr class="border-gray-100" />
                </div>

                <div class="ml-3">
                    <a href="<?= url('image-create') ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->images_current_month, $this->user->plan_settings->images_per_month_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('images.create') ?>
                    </a>
                </div>
            </div>

            <?php if(count($data->images)): ?>
                <div class="table-responsive table-custom-container">
                    <table class="table table-custom">
                        <thead>
                        <tr>
                            <th><?= l('global.image') ?></th>
                            <th><?= l('images.size') ?></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($data->images as $row): ?>

                            <tr>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <a href="<?= \Altum\Uploads::get_full_url('images') . $row->image ?>" target="_blank">
                                            <img src="<?= \Altum\Uploads::get_full_url('images') . $row->image ?>" class="img-fluid rounded mr-3" style="width: 50px; height: 50px;min-width: 50px; min-height: 50px;" data-toggle="tooltip" title="<?= l('global.view') ?>" alt="<?= $row->input ?>" />
                                        </a>

                                        <div class="d-flex flex-column">
                                            <a href="<?= url('image-update/' . $row->image_id) ?>"><?= $row->name ?></a>
                                            <small class="text-muted" data-toggle="tooltip" title="<?= string_truncate($row->input, 256) ?>"><?= string_truncate($row->input, 32) ?></small>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-nowrap">
                                    <span class="badge badge-light"><?= $row->size ?></span>
                                </td>

                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                        <i class="fas fa-fw fa-clock text-muted"></i>
                                    </span>

                                        <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_datetime) . ')</small>' : '<br />-')) ?>">
                                        <i class="fas fa-fw fa-history text-muted"></i>
                                    </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end">
                                        <?= include_view(THEME_PATH . 'views/images/image_dropdown_button.php', ['id' => $row->image_id, 'resource_name' => $row->name, 'image' => $row->image, 'image_url' => \Altum\Uploads::get_full_url('images') . $row->image]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td colspan="5">
                                <a href="<?= url('images') ?>" class="text-muted">
                                    <i class="fas fa-angle-right fa-sm fa-fw mr-1"></i> <?= l('global.view_more') ?>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            <?php else: ?>

                <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                    'filters_get' => $data->filters->get ?? [],
                    'name' => 'images',
                    'has_secondary_text' => true,
                ]); ?>

            <?php endif ?>
        </div>
    <?php endif ?>

    <?php if(settings()->aix->transcriptions_is_enabled): ?>
        <div class="mt-6 mb-4">
            <div class="row m-n2">
                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <a href="<?= url('transcriptions') ?>" class="stretched-link">
                                <i class="fas fa-fw fa-microphone-alt text-primary-600"></i>
                            </a>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('transcriptions.widget.total'), '<span class="h6">' . nr($data->total_transcriptions) . '</span>') ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" title="<?= l('transcriptions.widget.this_month') ?>">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <i class="fas fa-fw fa-file-audio text-primary-600"></i>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('transcriptions.widget.transcriptions_current_month'), '<span class="h6">' . nr($data->transcriptions_current_month) . '</span>') ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4 p-2 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden" data-toggle="tooltip" title="<?= l('transcriptions.widget.this_month') ?>">
                        <div class="px-3 d-flex flex-column justify-content-center">
                            <i class="fas fa-fw fa-volume-up text-primary-600"></i>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('transcriptions.widget.available_transcriptions'), '<span class="h6">' . ($this->user->plan_settings->transcriptions_per_month_limit != -1 ? nr($data->available_transcriptions) : l('global.unlimited')) . '</span>') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-4">
            <div class="d-flex align-items-center mb-3">
                <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-microphone-alt mr-1"></i> <?= l('dashboard.transcriptions_header') ?></h2>

                <div class="flex-fill">
                    <hr class="border-gray-100" />
                </div>

                <div class="ml-3">
                    <a href="<?= url('transcription-create') ?>" class="btn btn-sm btn-outline-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->transcriptions_current_month, $this->user->plan_settings->transcriptions_per_month_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('transcriptions.create') ?>
                    </a>
                </div>
            </div>

            <?php if(count($data->transcriptions)): ?>
                <div class="table-responsive table-custom-container">
                    <table class="table table-custom">
                        <thead>
                        <tr>
                            <th><?= l('transcriptions.transcription') ?></th>
                            <th><?= l('transcriptions.words') ?></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($data->transcriptions as $row): ?>

                            <tr>
                                <td class="text-nowrap">
                                    <div class="d-flex flex-column">
                                        <a href="<?= url('transcription-update/' . $row->transcription_id) ?>"><?= $row->name ?></a>
                                        <small class="text-muted" data-toggle="tooltip" title="<?= string_truncate($row->input, 256) ?>"><?= string_truncate($row->input, 32) ?></small>
                                    </div>
                                </td>

                                <td class="text-nowrap">
                                    <span class="text-muted"><?= nr($row->words) ?></span>
                                </td>

                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                            <i class="fas fa-fw fa-clock text-muted"></i>
                                        </span>

                                        <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->last_datetime) . ')</small>' : '<br />-')) ?>">
                                            <i class="fas fa-fw fa-history text-muted"></i>
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end">
                                        <?= include_view(THEME_PATH . 'views/transcriptions/transcription_dropdown_button.php', ['id' => $row->transcription_id, 'resource_name' => $row->name]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td colspan="5">
                                <a href="<?= url('transcriptions') ?>" class="text-muted">
                                    <i class="fas fa-angle-right fa-sm fa-fw mr-1"></i> <?= l('global.view_more') ?>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            <?php else: ?>

                <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                    'filters_get' => $data->filters->get ?? [],
                    'name' => 'transcriptions',
                    'has_secondary_text' => true,
                ]); ?>

            <?php endif ?>
        </div>
    <?php endif ?>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'document',
    'resource_id' => 'document_id',
    'has_dynamic_resource_name' => true,
    'path' => 'documents/delete'
]), 'modals'); ?>


<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'transcription',
    'resource_id' => 'transcription_id',
    'has_dynamic_resource_name' => true,
    'path' => 'transcriptions/delete'
]), 'modals'); ?>
