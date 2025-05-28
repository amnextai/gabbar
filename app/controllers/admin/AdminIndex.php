<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class AdminIndex extends Controller {

    public function index() {

        $documents = db()->getValue('documents', 'count(`document_id`)');
        $images = db()->getValue('images', 'count(`image_id`)');
        $transcriptions = db()->getValue('transcriptions', 'count(`transcription_id`)');
        $chats = db()->getValue('chats', 'count(`chat_id`)');
        $projects = db()->getValue('projects', 'count(`project_id`)');
        $users = db()->getValue('users', 'count(`user_id`)');

        /* Widgets stats: current month */
        extract(\Altum\Cache::cache_function_result('admin_dashboard_current_month', null, function() {
            return [
                'documents_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('documents', 'count(*)'),
                'images_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('images', 'count(*)'),
                'transcriptions_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('transcriptions', 'count(*)'),
                'chats_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('chats', 'count(*)'),
                'projects_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('projects', 'count(*)'),
                'users_current_month' => db()->where('datetime', date('Y-m-01'), '>=')->getValue('users', 'count(*)'),
                'payments_current_month' => in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('datetime', date('Y-m-01'), '>=')->getValue('payments', 'count(*)') : 0,
                'payments_amount_current_month' => in_array(settings()->license->type, ['Extended License', 'extended']) ? db()->where('datetime', date('Y-m-01'), '>=')->getValue('payments', 'sum(`total_amount_default_currency`)') : 0,
            ];
        }, 86400));

        /* Get currently active users */
        $fifteen_minutes_ago_datetime = (new \DateTime())->modify('-15 minutes')->format('Y-m-d H:i:s');
        $active_users = db()->where('last_activity', $fifteen_minutes_ago_datetime, '>=')->getValue('users', 'COUNT(*)');

        if(in_array(settings()->license->type, ['Extended License', 'extended'])) {
            $payments = db()->getValue('payments', 'count(`id`)');
            $payments_total_amount = db()->getValue('payments', 'sum(`total_amount_default_currency`)');
        } else {
            $payments = $payments_total_amount = 0;
        }

        if(settings()->internal_notifications->admins_is_enabled) {
            $internal_notifications = db()->where('for_who', 'admin')->orderBy('internal_notification_id', 'DESC')->get('internal_notifications', 5);

            $should_set_all_read = false;
            foreach($internal_notifications as $notification) {
                if(!$notification->is_read) $should_set_all_read = true;
            }

            if($should_set_all_read) {
                db()->where('for_who', 'admin')->update('internal_notifications', [
                    'is_read' => 1,
                    'read_datetime' => get_date(),
                ]);
            }
        }

        /* Requested plan details */
        $plans = (new \Altum\Models\Plan())->get_plans();

        /* Main View */
        $data = [
            'documents' => $documents,
            'images' => $images,
            'transcriptions' => $transcriptions,
            'chats' => $chats,
            'projects' => $projects,
            'users' => $users,
            'payments' => $payments,
            'payments_total_amount' => $payments_total_amount,

            'documents_current_month' => $documents_current_month,
            'images_current_month' => $images_current_month,
            'transcriptions_current_month' => $transcriptions_current_month,
            'chats_current_month' => $chats_current_month,
            'projects_current_month' => $projects_current_month,
            'users_current_month' => $users_current_month,
            'payments_current_month' => $payments_current_month,
            'payments_amount_current_month' => $payments_amount_current_month,

            'plans' => $plans,
            'active_users' => $active_users,
            'internal_notifications' => $internal_notifications ?? [],
        ];

        $view = new \Altum\View('admin/index/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
