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

class AdminReplacedBackgroundImages extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('replaced_background_image_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `replaced_background_images` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/replaced-background-images?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $replaced_background_images = [];
        $replaced_background_images_result = database()->query("
            SELECT
                `replaced_background_images`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `replaced_background_images`
            LEFT JOIN
                `users` ON `replaced_background_images`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('replaced_background_images')}
                {$filters->get_sql_order_by('replaced_background_images')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $replaced_background_images_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $replaced_background_images[] = $row;
        }

        /* Export handler */
        process_export_csv($replaced_background_images, 'include', ['replaced_background_image_id', 'project_id', 'user_id', 'name', 'original_image', 'replaced_background_image', 'datetime', 'last_datetime'], sprintf(l('replaced_background_images.title')));
        process_export_json($replaced_background_images, 'include', ['replaced_background_image_id', 'project_id', 'user_id', 'name', 'original_image', 'replaced_background_image', 'settings', 'datetime', 'last_datetime'], sprintf(l('replaced_background_images.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'replaced_background_images' => $replaced_background_images,
            'filters' => $filters,
            'pagination' => $pagination,
        ];

        $view = new \Altum\View('admin/replaced-background-images/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/replaced-background-images');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/replaced-background-images');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/replaced-background-images');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            set_time_limit(0);
            
            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $replaced_background_image_id) {

                        $image = db()->where('replaced_background_image_id', $replaced_background_image_id)->getOne('replaced_background_images', ['user_id', 'original_image', 'replaced_background_image']);

                        /* Delete file */
                        \Altum\Uploads::delete_uploaded_file($image->original_image, 'replaced_background_images');
                        \Altum\Uploads::delete_uploaded_file($image->replaced_background_image, 'replaced_background_images');

                        /* Delete the resource */
                        db()->where('replaced_background_image_id', $image->replaced_background_image_id)->delete('replaced_background_images');

                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/replaced-background-images');
    }

    public function delete() {

        $replaced_background_image_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('replaced_background_image_id', $replaced_background_image_id)->getOne('replaced_background_images', ['replaced_background_image_id', 'user_id', 'name', 'original_image', 'replaced_background_image'])) {
            redirect('admin/replaced-background-images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($image->original_image, 'replaced_background_images');
            \Altum\Uploads::delete_uploaded_file($image->replaced_background_image, 'replaced_background_images');

            /* Delete the resource */
            db()->where('replaced_background_image_id', $image->replaced_background_image_id)->delete('replaced_background_images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

        }

        redirect('admin/replaced-background-images');
    }

}
