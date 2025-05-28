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

class AdminRemovedBackgroundImages extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('removed_background_image_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `removed_background_images` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/removed-background-images?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $removed_background_images = [];
        $removed_background_images_result = database()->query("
            SELECT
                `removed_background_images`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `removed_background_images`
            LEFT JOIN
                `users` ON `removed_background_images`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('removed_background_images')}
                {$filters->get_sql_order_by('removed_background_images')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $removed_background_images_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $removed_background_images[] = $row;
        }

        /* Export handler */
        process_export_csv($removed_background_images, 'include', ['removed_background_image_id', 'project_id', 'user_id', 'name', 'original_image', 'removed_background_image', 'datetime', 'last_datetime'], sprintf(l('removed_background_images.title')));
        process_export_json($removed_background_images, 'include', ['removed_background_image_id', 'project_id', 'user_id', 'name', 'original_image', 'removed_background_image', 'settings', 'datetime', 'last_datetime'], sprintf(l('removed_background_images.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'removed_background_images' => $removed_background_images,
            'filters' => $filters,
            'pagination' => $pagination,
        ];

        $view = new \Altum\View('admin/removed-background-images/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/removed-background-images');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/removed-background-images');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/removed-background-images');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);
            
            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $removed_background_image_id) {

                        $image = db()->where('removed_background_image_id', $removed_background_image_id)->getOne('removed_background_images', ['user_id', 'original_image', 'removed_background_image']);

                        /* Delete file */
                        \Altum\Uploads::delete_uploaded_file($image->original_image, 'removed_background_images');
                        \Altum\Uploads::delete_uploaded_file($image->removed_background_image, 'removed_background_images');

                        /* Delete the resource */
                        db()->where('removed_background_image_id', $image->removed_background_image_id)->delete('removed_background_images');

                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/removed-background-images');
    }

    public function delete() {

        $removed_background_image_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('removed_background_image_id', $removed_background_image_id)->getOne('removed_background_images', ['removed_background_image_id', 'user_id', 'name', 'original_image', 'removed_background_image'])) {
            redirect('admin/removed-background-images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($image->original_image, 'removed_background_images');
            \Altum\Uploads::delete_uploaded_file($image->removed_background_image, 'removed_background_images');

            /* Delete the resource */
            db()->where('removed_background_image_id', $image->removed_background_image_id)->delete('removed_background_images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

        }

        redirect('admin/removed-background-images');
    }

}
