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

class UpscaledImageUpdate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!settings()->aix->upscaled_images_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $upscaled_image_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get image details */
        if(!$upscaled_image = db()->where('upscaled_image_id', $upscaled_image_id)->where('user_id', $this->user->user_id)->getOne('upscaled_images')) {
            redirect();
        }

        $upscaled_image->settings = json_decode($upscaled_image->settings ?? '');

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
                db()->where('upscaled_image_id', $upscaled_image->upscaled_image_id)->update('upscaled_images', [
                    'project_id' => $_POST['project_id'],
                    'name' => $_POST['name'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('upscaled-image-update/' . $upscaled_image->upscaled_image_id);
            }
        }

        /* Set a custom title */
        Title::set(sprintf(l('upscaled_image_update.title'), $upscaled_image->name));

        /* Main View */
        $data = [
            'upscaled_image' => $upscaled_image,
            'projects' => $projects ?? [],
        ];

        $view = new \Altum\View(THEME_PATH . 'views/upscaled-image-update/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

}
