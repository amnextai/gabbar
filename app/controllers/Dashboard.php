<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class Dashboard extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        $data = [];

        $usage = db()->where('user_id', $this->user->user_id)->getOne('users', ['aix_documents_current_month', 'aix_words_current_month', 'aix_images_current_month', 'aix_transcriptions_current_month']);

        if(settings()->aix->documents_is_enabled) {
            /* Stats */
            $total_documents = db()->where('user_id', $this->user->user_id)->getValue('documents', 'count(`document_id`)');

            /* Get the documents */
            $documents = [];
            $documents_result = database()->query("SELECT * FROM `documents` WHERE `user_id` = {$this->user->user_id} ORDER BY `document_id` DESC LIMIT 5");
            while($row = $documents_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $documents[] = $row;
            }

            /* Documents */
            $documents_current_month = $usage->aix_documents_current_month;
            $available_documents = $this->user->plan_settings->documents_per_month_limit - $documents_current_month;

            /* Available words */
            $words_current_month = $usage->aix_words_current_month;
            $available_words = $this->user->plan_settings->words_per_month_limit - $words_current_month;

            /* Get available templates categories */
            $templates_categories = (new \Altum\Models\TemplatesCategories())->get_templates_categories();

            /* Templates */
            $templates = (new \Altum\Models\Templates())->get_templates();

            $data = array_merge($data, [
                'documents' => $documents,
                'total_documents' => $total_documents,
                'documents_current_month' => $documents_current_month,
                'available_documents' => $available_documents,
                'words_current_month' => $words_current_month,
                'available_words' => $available_words,
                'templates' => $templates,
                'templates_categories' => $templates_categories,
            ]);
        }

        if(settings()->aix->images_is_enabled) {
            /* Stats */
            $total_images = db()->where('user_id', $this->user->user_id)->getValue('images', 'count(`image_id`)');

            /* Get the images */
            $images = [];
            $images_result = database()->query("SELECT * FROM `images` WHERE `user_id` = {$this->user->user_id} ORDER BY `image_id` DESC LIMIT 5");
            while($row = $images_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $images[] = $row;
            }

            /* Images */
            $available_images = $this->user->plan_settings->images_per_month_limit - $usage->aix_images_current_month;

            $data = array_merge($data, [
                'images' => $images,
                'total_images' => $total_images,
                'images_current_month' => $usage->aix_images_current_month,
                'available_images' => $available_images,
            ]);
        }

        if(settings()->aix->transcriptions_is_enabled) {
            /* Stats */
            $total_transcriptions = db()->where('user_id', $this->user->user_id)->getValue('transcriptions', 'count(`transcription_id`)');

            /* Get the transcriptions */
            $transcriptions = [];
            $transcriptions_result = database()->query("SELECT * FROM `transcriptions` WHERE `user_id` = {$this->user->user_id} ORDER BY `transcription_id` DESC LIMIT 5");
            while($row = $transcriptions_result->fetch_object()) {
                $row->settings = json_decode($row->settings ?? '');
                $transcriptions[] = $row;
            }

            /* Transcriptions */
            $available_transcriptions = $this->user->plan_settings->transcriptions_per_month_limit - $usage->aix_transcriptions_current_month;

            $data = array_merge($data, [
                'transcriptions' => $transcriptions,
                'total_transcriptions' => $total_transcriptions,
                'transcriptions_current_month' => $usage->aix_transcriptions_current_month,
                'available_transcriptions' => $available_transcriptions,
            ]);
        }

        /* Prepare the view */
        $view = new \Altum\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
