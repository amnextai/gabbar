<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a href="<?= $data->removed_background_image_url ?>" target="_blank" download="<?= $data->removed_background_image ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-download mr-2"></i> <?= l('global.download') ?></a>
        <a href="#" data-toggle="modal" data-target="#removed_background_image_delete_modal" data-removed-background-image-id="<?= $data->id ?>" data-resource-name="<?= $data->resource_name ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'removed_background_image',
    'resource_id' => 'removed_background_image_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/removed-background-images/delete/'
]), 'modals', 'removed_background_image_delete_modal'); ?>
