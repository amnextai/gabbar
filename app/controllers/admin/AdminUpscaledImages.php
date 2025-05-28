<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;

class AdminUpscaledImages extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'scale'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('upscaled_image_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `upscaled_images` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/upscaled-images?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $upscaled_images = [];
        $upscaled_images_result = database()->query("
            SELECT
                `upscaled_images`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `upscaled_images`
            LEFT JOIN
                `users` ON `upscaled_images`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('upscaled_images')}
                {$filters->get_sql_order_by('upscaled_images')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $upscaled_images_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $upscaled_images[] = $row;
        }

        /* Export handler */
        process_export_csv($upscaled_images, 'include', ['upscaled_image_id', 'project_id', 'user_id', 'name', 'original_image', 'upscaled_image', 'original_size', 'upscaled_size', 'scale', 'datetime', 'last_datetime'], sprintf(l('upscaled_images.title')));
        process_export_json($upscaled_images, 'include', ['upscaled_image_id', 'project_id', 'user_id', 'name', 'original_image', 'upscaled_image', 'original_size', 'upscaled_size', 'scale', 'settings', 'datetime', 'last_datetime'], sprintf(l('upscaled_images.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'upscaled_images' => $upscaled_images,
            'filters' => $filters,
            'pagination' => $pagination,
        ];

        $view = new \Altum\View('admin/upscaled-images/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/upscaled-images');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/upscaled-images');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/upscaled-images');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);
            
            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $upscaled_image_id) {

                        $image = db()->where('upscaled_image_id', $upscaled_image_id)->getOne('upscaled_images', ['user_id', 'original_image', 'upscaled_image']);

                        /* Delete file */
                        \Altum\Uploads::delete_uploaded_file($image->original_image, 'upscaled_images');
                        \Altum\Uploads::delete_uploaded_file($image->upscaled_image, 'upscaled_images');

                        /* Delete the resource */
                        db()->where('upscaled_image_id', $image->upscaled_image_id)->delete('upscaled_images');

                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/upscaled-images');
    }

    public function delete() {

        $upscaled_image_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('upscaled_image_id', $upscaled_image_id)->getOne('upscaled_images', ['upscaled_image_id', 'user_id', 'name', 'original_image', 'upscaled_image'])) {
            redirect('admin/upscaled-images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($image->original_image, 'upscaled_images');
            \Altum\Uploads::delete_uploaded_file($image->upscaled_image, 'upscaled_images');

            /* Delete the resource */
            db()->where('upscaled_image_id', $image->upscaled_image_id)->delete('upscaled_images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

        }

        redirect('admin/upscaled-images');
    }

}
