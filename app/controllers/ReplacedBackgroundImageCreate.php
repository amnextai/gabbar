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
use Altum\Response;
use Altum\Uploads;

class ReplacedBackgroundImageCreate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!settings()->aix->replaced_background_images_is_enabled) {
            redirect('dashboard');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.replaced_background_images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('replaced-background-images');
        }

        /* Check for the plan limit */
        $replaced_background_images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_replaced_background_images_current_month`');
        if($this->user->plan_settings->replaced_background_images_per_month_limit != -1 && $replaced_background_images_current_month >= $this->user->plan_settings->replaced_background_images_per_month_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('replaced-background-images');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->clipdrop_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.clipdrop_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Clear $_GET */
        foreach($_GET as $key => $value) {
            $_GET[$key] = input_clean($value);
        }

        $values = [
            'name' => $_POST['name'] ?? $_GET['name'] ?? sprintf(l('replaced_background_image_create.name_x'), \Altum\Date::get()),
            'input' => $_GET['input'] ?? $_POST['input'] ?? '',
            'project_id' => $_GET['project_id'] ?? $_POST['project_id'] ?? null,
        ];

        /* Prepare the view */
        $data = [
            'values' => $values,
            'projects' => $projects ?? [],
        ];

        $view = new \Altum\View(THEME_PATH . 'views/replaced-background-image-create/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));

    }

    public function create_ajax() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(empty($_POST)) {
            redirect();
        }

        set_time_limit(0);

        \Altum\Authentication::guard();

        if(!settings()->aix->replaced_background_images_is_enabled) {
            redirect('dashboard');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.replaced_background_images')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Check for the plan limit */
        $replaced_background_images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_replaced_background_images_current_month`');
        if($this->user->plan_settings->replaced_background_images_per_month_limit != -1 && $replaced_background_images_current_month >= $this->user->plan_settings->replaced_background_images_per_month_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        $_POST['name'] = input_clean($_POST['name'], 64);
        $_POST['input'] = input_clean($_POST['input'], 1000);
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;

        /* Check for any errors */
        $required_fields = ['name', 'input'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(empty($_FILES['original_image']['name'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        \Altum\Uploads::validate_upload('replaced_background_images', 'original_image', $this->user->plan_settings->replaced_background_images_file_size_limit);

        /* Try to increase the database timeout as well */
        database()->query("set session wait_timeout=600;");

        /* Do not use sessions anymore to not lockout the user from doing anything else on the site */
        session_write_close();

        try {
            $response = \Unirest\Request::post(
                'https://clipdrop-api.co/replace-background/v1',
                [
                    'x-api-key' => get_random_line_from_text($this->user->plan_settings->exclusive_personal_api_keys ? $this->user->preferences->clipdrop_api_key : settings()->aix->clipdrop_api_key),
                ],
                \Unirest\Request\Body::multipart([
                    'prompt' => $_POST['input']
                ], [
                    'image_file' => $_FILES['original_image']['tmp_name']
                ])
            );

            if($response->code >= 400) {
                /* Delete temp */
                unlink($_FILES['original_image']['tmp_name']);

                Response::json($response->body->error, 'error');
            }

        } catch (\Exception $exception) {
            /* Delete temp */
            unlink($_FILES['original_image']['tmp_name']);

            Response::json($exception->getMessage(), 'error');
        }

        /* Save the image */
        $original_image = \Altum\Uploads::process_upload(null, 'replaced_background_images', 'original_image', 'original_image_remove', $this->user->plan_settings->replaced_background_images_file_size_limit);

        /* Generate new name for image */
        $replaced_background_image = md5(time() . rand() . rand()) . '.png';

        /* Save the new image */
        sleep(1);
        $temp_replaced_background_image_full_path = UPLOADS_PATH . Uploads::get_path('replaced_background_images') . $replaced_background_image;
        file_put_contents($temp_replaced_background_image_full_path, $response->raw_body);

        /* Fake uploaded */
        $_FILES['replaced_background_images'] = [
            'name' => $replaced_background_image,
            'tmp_name' => $temp_replaced_background_image_full_path,
            'error' => null,
            'size' => 0,
        ];

        $replaced_background_image = \Altum\Uploads::process_upload_fake('replaced_background_images', 'replaced_background_images', 'json_error', null);

        /* Get info after the request */
        $info = \Unirest\Request::getInfo();

        /* Some needed variables */
        $api_response_time = $info['total_time'] * 1000;

        $settings = json_encode([]);

        /* Database query */
        $replaced_background_image_id = db()->insert('replaced_background_images', [
            'user_id' => $this->user->user_id,
            'project_id' => $_POST['project_id'],
            'name' => $_POST['name'],
            'input' => $_POST['input'],
            'original_image' => $original_image,
            'replaced_background_image' => $replaced_background_image,
            'settings' => $settings,
            'api_response_time' => $api_response_time,
            'datetime' => get_date(),
        ]);

        /* Database query */
        db()->where('user_id', $this->user->user_id)->update('users', [
            'aix_replaced_background_images_current_month' => db()->inc(1)
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'), 'success', ['url' => url('replaced-background-image-update/' . $replaced_background_image_id)]);

    }

}
