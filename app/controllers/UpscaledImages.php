<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\controllers;

use Altum\Alerts;

class UpscaledImages extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!settings()->aix->upscaled_images_is_enabled) {
            redirect('not-found');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->clipdrop_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.clipdrop_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'scale'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('upscaled_image_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `upscaled_images` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('upscaled-images?' . $filters->get_get() . '&page=%d')));

        /* Get the images */
        $upscaled_images = [];
        $upscaled_images_result = database()->query("
            SELECT
                *
            FROM
                `upscaled_images`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
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
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Available */
        $upscaled_images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_upscaled_images_current_month`');

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'upscaled_images' => $upscaled_images,
            'total_images' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'upscaled_images_current_month' => $upscaled_images_current_month,
        ];

        $view = new \Altum\View(THEME_PATH . 'views/upscaled-images/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {

        \Altum\Authentication::guard();

        if(!settings()->aix->upscaled_images_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('upscaled-images');
        }

        if(empty($_POST)) {
            redirect('upscaled-images');
        }

        $upscaled_image_id = (int) query_clean($_POST['upscaled_image_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('upscaled_image_id', $upscaled_image_id)->where('user_id', $this->user->user_id)->getOne('upscaled_images', ['upscaled_image_id', 'name', 'upscaled_image', 'original_image'])) {
            redirect('upscaled-images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($image->upscaled_image, 'upscaled_images');
            \Altum\Uploads::delete_uploaded_file($image->original_image, 'upscaled_images');

            /* Delete the resource */
            db()->where('upscaled_image_id', $upscaled_image_id)->delete('upscaled_images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

            redirect('upscaled-images');
        }

        redirect('upscaled-images');
    }

}
