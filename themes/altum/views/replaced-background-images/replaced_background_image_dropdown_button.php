<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a href="<?= url('replaced-background-image-update/' . $data->id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?></a>
        <a href="<?= $data->replaced_background_image_url ?>" target="_blank" download="<?= $data->replaced_background_image ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-download mr-2"></i> <?= l('global.download') ?></a>
        <a href="#" data-toggle="modal" data-target="#replaced_background_image_delete_modal" data-replaced-background-image-id="<?= $data->id ?>" data-resource-name="<?= $data->resource_name ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

