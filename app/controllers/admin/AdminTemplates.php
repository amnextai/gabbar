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

class AdminTemplates extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['template_category_id'], ['name'], ['name', 'total_usage']));
        $filters->set_default_order_by('template_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `templates` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/templates?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $templates = [];
        $templates_result = database()->query("
            SELECT
                *
            FROM
                `templates`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $templates_result->fetch_object()) {
            $templates[] = $row;
        }

        /* Export handler */
        process_export_json($templates, 'include', ['template_id', 'template_category_id', 'name', 'prompt', 'settings', 'icon', 'order', 'total_usage', 'is_enabled', 'datetime', 'last_datetime']);
        process_export_csv($templates, 'include', ['template_id', 'template_category_id', 'name', 'prompt', 'icon', 'order', 'total_usage', 'is_enabled', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get available templates categories */
        $templates_categories = (new \Altum\Models\TemplatesCategories())->get_templates_categories();

        /* Main View */
        $data = [
            'templates' => $templates,
            'templates_categories' => $templates_categories,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/templates/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function duplicate() {

        if(empty($_POST)) {
            redirect('admin/templates');
        }

        $template_id = (int) $_POST['template_id'];

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$template = db()->where('template_id', $template_id)->getOne('templates')) {
            redirect('admin/templates');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Insert to database */
            $template_id = db()->insert('templates', [
                'template_category_id' => $template->template_category_id,
                'name' => string_truncate($template->name . ' - ' . l('global.duplicated'), 64, null),
                'prompt' => $template->prompt,
                'settings' => $template->settings,
                'icon' => $template->icon,
                'order' => $template->order + 1,
                'is_enabled' => $template->is_enabled,
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($template->name) . '</strong>'));

            /* Redirect */
            redirect('admin/template-update/' . $template_id);

        }

        redirect('admin/templates');
    }

    public function delete() {

        $template_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$template = db()->where('template_id', $template_id)->getOne('templates', ['template_id', 'name'])) {
            redirect('admin/templates');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('template_id', $template_id)->delete('templates');

            /* Clear the cache */
            cache()->deleteItem('templates');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $template->name . '</strong>'));

        }

        redirect('admin/templates');
    }

}
