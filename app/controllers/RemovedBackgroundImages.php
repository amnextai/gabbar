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

class RemovedBackgroundImages extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!settings()->aix->removed_background_images_is_enabled) {
            redirect('not-found');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->clipdrop_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.clipdrop_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('removed_background_image_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `removed_background_images` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('removed-background-images?' . $filters->get_get() . '&page=%d')));

        /* Get the images */
        $removed_background_images = [];
        $removed_background_images_result = database()->query("
            SELECT
                *
            FROM
                `removed_background_images`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
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
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Available */
        $removed_background_images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_removed_background_images_current_month`');

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'removed_background_images' => $removed_background_images,
            'total_images' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'removed_background_images_current_month' => $removed_background_images_current_month,
        ];

        $view = new \Altum\View(THEME_PATH . 'views/removed-background-images/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {

        \Altum\Authentication::guard();

        if(!settings()->aix->removed_background_images_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('removed-background-images');
        }

        if(empty($_POST)) {
            redirect('removed-background-images');
        }

        $removed_background_image_id = (int) query_clean($_POST['removed_background_image_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('removed_background_image_id', $removed_background_image_id)->where('user_id', $this->user->user_id)->getOne('removed_background_images', ['removed_background_image_id', 'name', 'removed_background_image', 'original_image'])) {
            redirect('removed-background-images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($image->removed_background_image, 'removed_background_images');
            \Altum\Uploads::delete_uploaded_file($image->original_image, 'removed_background_images');

            /* Delete the resource */
            db()->where('removed_background_image_id', $removed_background_image_id)->delete('removed_background_images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

            redirect('removed-background-images');
        }

        redirect('removed-background-images');
    }

}
