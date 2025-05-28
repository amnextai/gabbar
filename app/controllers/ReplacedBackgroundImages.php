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

class ReplacedBackgroundImages extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!settings()->aix->replaced_background_images_is_enabled) {
            redirect('dashboard');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->clipdrop_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.clipdrop_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('replaced_background_image_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `replaced_background_images` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('replaced-background-images?' . $filters->get_get() . '&page=%d')));

        /* Get the images */
        $replaced_background_images = [];
        $replaced_background_images_result = database()->query("
            SELECT
                *
            FROM
                `replaced_background_images`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
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
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Available */
        $replaced_background_images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_replaced_background_images_current_month`');

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'replaced_background_images' => $replaced_background_images,
            'total_images' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'replaced_background_images_current_month' => $replaced_background_images_current_month,
        ];

        $view = new \Altum\View(THEME_PATH . 'views/replaced-background-images/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {

        \Altum\Authentication::guard();

        if(!settings()->aix->replaced_background_images_is_enabled) {
            redirect('dashboard');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('replaced-background-images');
        }

        if(empty($_POST)) {
            redirect('replaced-background-images');
        }

        $replaced_background_image_id = (int) query_clean($_POST['replaced_background_image_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('replaced_background_image_id', $replaced_background_image_id)->where('user_id', $this->user->user_id)->getOne('replaced_background_images', ['replaced_background_image_id', 'name', 'replaced_background_image', 'original_image'])) {
            redirect('replaced-background-images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($image->replaced_background_image, 'replaced_background_images');
            \Altum\Uploads::delete_uploaded_file($image->original_image, 'replaced_background_images');

            /* Delete the resource */
            db()->where('replaced_background_image_id', $replaced_background_image_id)->delete('replaced_background_images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

            redirect('replaced-background-images');
        }

        redirect('replaced-background-images');
    }

}
