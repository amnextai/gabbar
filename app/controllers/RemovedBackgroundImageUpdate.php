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
use Altum\Title;

class RemovedBackgroundImageUpdate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!settings()->aix->removed_background_images_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $removed_background_image_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get image details */
        if(!$removed_background_image = db()->where('removed_background_image_id', $removed_background_image_id)->where('user_id', $this->user->user_id)->getOne('removed_background_images')) {
            redirect();
        }

        $removed_background_image->settings = json_decode($removed_background_image->settings ?? '');

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('removed_background_image_id', $removed_background_image->removed_background_image_id)->update('removed_background_images', [
                    'project_id' => $_POST['project_id'],
                    'name' => $_POST['name'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('removed-background-image-update/' . $removed_background_image->removed_background_image_id);
            }
        }

        /* Set a custom title */
        Title::set(sprintf(l('removed_background_image_update.title'), $removed_background_image->name));

        /* Main View */
        $data = [
            'removed_background_image' => $removed_background_image,
            'projects' => $projects ?? [],
        ];

        $view = new \Altum\View(THEME_PATH . 'views/removed-background-image-update/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

}
