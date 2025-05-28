<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('replaced-background-images') ?>"><?= l('replaced_background_images.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('replaced_background_image_update.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-photo-film mr-1"></i> <?= sprintf(l('global.update_x'), $data->replaced_background_image->name) ?></h1>

        <div class="d-flex align-items-center col-auto p-0">
            <?= include_view(THEME_PATH . 'views/replaced-background-images/replaced_background_image_dropdown_button.php', ['id' => $data->replaced_background_image->replaced_background_image_id, 'resource_name' => $data->replaced_background_image->name, 'replaced_background_image' => $data->replaced_background_image->replaced_background_image, 'replaced_background_image_url' => Altum\Uploads::get_full_url('replaced_background_images') . $data->replaced_background_image->replaced_background_image]) ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $data->replaced_background_image->name ?>" required="required" />
                </div>

                <div class="form-group">
                    <label><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('replaced_background_images.replaced_background_image') ?></label>

                    <div class="position-relative">
                        <div style="background: url('<?= Altum\Uploads::get_full_url('replaced_background_images') . $data->replaced_background_image->replaced_background_image ?>'); z-index: 1; background-size: cover; width: 100%; height: 100%; opacity: .25; filter: blur(10px);" class="position-absolute"></div>
                        <div class="text-center w-100 h-100 p-5 position-absolute" style="z-index: 2;">
                            <img src="<?= Altum\Uploads::get_full_url('replaced_background_images') . $data->replaced_background_image->replaced_background_image ?>" class="img-fluid rounded shadow-lg" />
                        </div>
                        <div class="text-center p-5">
                            <img src="<?= Altum\Uploads::get_full_url('replaced_background_images') . $data->replaced_background_image->replaced_background_image ?>" class="img-fluid rounded" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('replaced_background_images.original_image') ?></label>

                    <div class="position-relative">
                        <div style="background: url('<?= Altum\Uploads::get_full_url('replaced_background_images') . $data->replaced_background_image->original_image ?>'); z-index: 1; background-size: cover; width: 100%; height: 100%; opacity: .25; filter: blur(10px);" class="position-absolute"></div>
                        <div class="text-center w-100 h-100 p-5 position-absolute" style="z-index: 2;">
                            <img src="<?= Altum\Uploads::get_full_url('replaced_background_images') . $data->replaced_background_image->original_image ?>" class="img-fluid rounded shadow-lg" />
                        </div>
                        <div class="text-center p-5">
                            <img src="<?= Altum\Uploads::get_full_url('replaced_background_images') . $data->replaced_background_image->original_image ?>" class="img-fluid rounded" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                        <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                        <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                    </div>
                    <select id="project_id" name="project_id" class="custom-select">
                        <option value=""><?= l('global.none') ?></option>
                        <?php foreach($data->projects as $project_id => $project): ?>
                            <option value="<?= $project_id ?>" <?= $data->replaced_background_image->project_id == $project_id ? 'selected="selected"' : null ?>><?= $project->name ?></option>
                        <?php endforeach ?>
                    </select>
                    <small class="form-text text-muted"><?= l('projects.project_id_help') ?></small>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
            </form>

        </div>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'replaced_background_image',
    'resource_id' => 'replaced_background_image_id',
    'has_dynamic_resource_name' => true,
    'path' => 'replaced-background-images/delete'
]), 'modals'); ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

