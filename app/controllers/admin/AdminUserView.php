<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Models\Plan;
use Altum\Title;

class AdminUserView extends Controller {

    public function index() {

        $user_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$user = db()->where('user_id', $user_id)->getOne('users')) {
            redirect('admin/users');
        }

        /* Get widget stats */
        $documents = db()->where('user_id', $user_id)->getValue('documents', 'count(`document_id`)');
        $images = db()->where('user_id', $user_id)->getValue('images', 'count(`image_id`)');
        $upscaled_images = db()->where('user_id', $user_id)->getValue('upscaled_images', 'count(`upscaled_image_id`)');
        $removed_background_images = db()->where('user_id', $user_id)->getValue('removed_background_images', 'count(`removed_background_image_id`)');
        $replaced_background_images = db()->where('user_id', $user_id)->getValue('replaced_background_images', 'count(`replaced_background_image_id`)');
        $transcriptions = db()->where('user_id', $user_id)->getValue('transcriptions', 'count(`transcription_id`)');
        $syntheses = db()->where('user_id', $user_id)->getValue('syntheses', 'count(`synthesis_id`)');
        $chats = db()->where('user_id', $user_id)->getValue('chats', 'count(`chat_id`)');
        $projects = db()->where('user_id', $user_id)->getValue('projects', 'count(`project_id`)');
        $payments = in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('user_id', $user_id)->getValue('payments', 'count(`id`)') : 0;

        /* Get the current plan details */
        $user->plan = (new Plan())->get_plan_by_id($user->plan_id);

        /* Check if its a custom plan */
        if($user->plan_id == 'custom') {
            $user->plan->settings = $user->plan_settings;
        }

        $user->billing = json_decode($user->billing);

        /* Set a custom title */
        Title::set(sprintf(l('admin_user_view.title'), $user->name . ' (' . $user->email . ')'));

        /* Main View */
        $data = [
            'user' => $user,
            'documents' => $documents,
            'upscaled_images' => $upscaled_images,
            'removed_background_images' => $removed_background_images,
            'replaced_background_images' => $replaced_background_images,
            'images' => $images,
            'transcriptions' => $transcriptions,
            'syntheses' => $syntheses,
            'chats' => $chats,
            'projects' => $projects,
            'payments' => $payments,
        ];

        $view = new \Altum\View('admin/user-view/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
