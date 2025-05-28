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

class AdminPlanCreate extends Controller {

    public function index() {

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            /* Get the available taxes from the system */
            $taxes = db()->get('taxes');
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['description'] = input_clean($_POST['description'], 256);

            /* Prices */
            $prices = [
                'monthly' => [],
                'annual' => [],
                'lifetime' => [],
            ];

            foreach((array) settings()->payment->currencies as $currency => $currency_data) {
                $prices['monthly'][$currency] = (float) $_POST['monthly_price'][$currency];
                $prices['annual'][$currency] = (float) $_POST['annual_price'][$currency];
                $prices['lifetime'][$currency] = (float) $_POST['lifetime_price'][$currency];
            }

            $prices = json_encode($prices);

            $_POST['settings'] = [
                'exclusive_personal_api_keys'       => isset($_POST['exclusive_personal_api_keys']),
                'documents_model'                   => $_POST['documents_model'],
                'documents_per_month_limit'         => (int) $_POST['documents_per_month_limit'],
                'words_per_month_limit'             => (int) $_POST['words_per_month_limit'],
                'images_api'                        => $_POST['images_api'],
                'images_per_month_limit'            => (int) $_POST['images_per_month_limit'],
                'upscaled_images_per_month_limit'   => (int) $_POST['upscaled_images_per_month_limit'],
                'upscaled_images_file_size_limit'    => $_POST['upscaled_images_file_size_limit'] > get_max_upload() || $_POST['upscaled_images_file_size_limit'] < 0 ? get_max_upload() : (float) $_POST['upscaled_images_file_size_limit'],
                'removed_background_images_per_month_limit'     => (int) $_POST['removed_background_images_per_month_limit'],
                'removed_background_images_file_size_limit'     => $_POST['removed_background_images_file_size_limit'] > get_max_upload() || $_POST['removed_background_images_file_size_limit'] < 0 || $_POST['removed_background_images_file_size_limit'] > 30 ? (get_max_upload() > 30 ? 30 : get_max_upload()) : (float) $_POST['removed_background_images_file_size_limit'],
                'replaced_background_images_per_month_limit'    => (int) $_POST['replaced_background_images_per_month_limit'],
                'replaced_background_images_file_size_limit'    => $_POST['replaced_background_images_file_size_limit'] > get_max_upload() || $_POST['replaced_background_images_file_size_limit'] < 0 || $_POST['replaced_background_images_file_size_limit'] > 30 ? (get_max_upload() > 30 ? 30 : get_max_upload()) : (float) $_POST['replaced_background_images_file_size_limit'],
                'transcriptions_per_month_limit'    => (int) $_POST['transcriptions_per_month_limit'],
                'transcriptions_file_size_limit'    => $_POST['transcriptions_file_size_limit'] > get_max_upload() || $_POST['transcriptions_file_size_limit'] < 0 || $_POST['transcriptions_file_size_limit'] > 25 ? (get_max_upload() > 25 ? 25 : get_max_upload()) : (float) $_POST['transcriptions_file_size_limit'],
                'chats_model'                       => $_POST['chats_model'],
                'chats_per_month_limit'             => (int) $_POST['chats_per_month_limit'],
                'chat_messages_per_chat_limit'      => (int) $_POST['chat_messages_per_chat_limit'],
                'chat_image_size_limit'    => $_POST['chat_image_size_limit'] > get_max_upload() || $_POST['chat_image_size_limit'] < 0 || $_POST['chat_image_size_limit'] > 20 ? (get_max_upload() > 20 ? 20 : get_max_upload()) : (float) $_POST['chat_image_size_limit'],
                'syntheses_api'                     => $_POST['syntheses_api'],
                'syntheses_per_month_limit'         => (int) $_POST['syntheses_per_month_limit'],
                'synthesized_characters_per_month_limit' => (int) $_POST['synthesized_characters_per_month_limit'],
                'projects_limit'                    => (int) $_POST['projects_limit'],
                'teams_limit'                       => (int) $_POST['teams_limit'],
                'team_members_limit'                => (int) $_POST['team_members_limit'],
                'api_is_enabled'                    => isset($_POST['api_is_enabled']),
                'affiliate_commission_percentage'   => (int) $_POST['affiliate_commission_percentage'],
                'no_ads'                            => isset($_POST['no_ads']),
            ];

            $_POST['settings'] = json_encode($_POST['settings']);

            $_POST['color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['color']) ? null : $_POST['color'];
            $_POST['status'] = (int) $_POST['status'];
            $_POST['order'] = (int) $_POST['order'];
            $_POST['trial_days'] = (int) $_POST['trial_days'];
            $_POST['taxes_ids'] = json_encode($_POST['taxes_ids'] ?? []);

            /* Translations */
            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    $_POST['translations'][$language_name][$key] = input_clean($value);
                }
                if(!array_key_exists($language_name, \Altum\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            $_POST['translations'] = json_encode($_POST['translations']);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

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
                db()->insert('plans', [
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'prices' => $prices,
                    'settings' => $_POST['settings'],
                    'translations' => $_POST['translations'],
                    'taxes_ids' => $_POST['taxes_ids'],
                    'color' => $_POST['color'],
                    'status' => $_POST['status'],
                    'order' => $_POST['order'],
                    'datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('plans');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/plans');
            }
        }


        /* Main View */
        $data = [
            'taxes' => $taxes ?? null,
        ];

        $view = new \Altum\View('admin/plan-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
