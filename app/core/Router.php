<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum;

class Router {
    public static $params = [];
    public static $original_request = '';
    public static $original_request_query = '';
    public static $language_code = '';
    public static $path = '';
    public static $controller_key = 'index';
    public static $controller = 'Index';
    public static $controller_settings = [
        'wrapper' => 'wrapper',
        'no_authentication_check' => false,

        /* Enable / disable browser language detection & redirection */
        'no_browser_language_detection' => false,

        /* Enable / disable browser language detection & redirection */
        'allow_indexing' => true,

        /* Should we see a view for the controller? */
        'has_view' => true,

        /* Footer currency display */
        'currency_switcher' => false,

        /* If set on yes, ads won't show on these pages at all */
        'ads' => false,

        /* Authentication guard check (potential values: null, 'guest', 'user', 'admin') */
        'authentication' => null,

        /* Teams */
        'allow_team_access' => null,
    ];
    public static $method = 'index';
    public static $data = [];

    public static $routes = [
        '' => [
            'dashboard' => [
                'controller' => 'Dashboard',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'templates' => [
                'controller' => 'Templates',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'documents' => [
                'controller' => 'Documents',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'document-update' => [
                'controller' => 'DocumentUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'document-create' => [
                'controller' => 'DocumentCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'images' => [
                'controller' => 'Images',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'image-update' => [
                'controller' => 'ImageUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'image-create' => [
                'controller' => 'ImageCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'upscaled-images' => [
                'controller' => 'UpscaledImages',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'upscaled-image-update' => [
                'controller' => 'UpscaledImageUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'upscaled-image-create' => [
                'controller' => 'UpscaledImageCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'removed-background-images' => [
                'controller' => 'RemovedBackgroundImages',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'removed-background-image-update' => [
                'controller' => 'RemovedBackgroundImageUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'removed-background-image-create' => [
                'controller' => 'RemovedBackgroundImageCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'replaced-background-images' => [
                'controller' => 'ReplacedBackgroundImages',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'replaced-background-image-update' => [
                'controller' => 'ReplacedBackgroundImageUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'replaced-background-image-create' => [
                'controller' => 'ReplacedBackgroundImageCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'transcriptions' => [
                'controller' => 'Transcriptions',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'transcription-update' => [
                'controller' => 'TranscriptionUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'transcription-create' => [
                'controller' => 'TranscriptionCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'chats' => [
                'controller' => 'Chats',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'chat' => [
                'controller' => 'Chat',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'chat-create' => [
                'controller' => 'ChatCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'syntheses' => [
                'controller' => 'Syntheses',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'synthesis-update' => [
                'controller' => 'SynthesisUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'synthesis-create' => [
                'controller' => 'SynthesisCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'projects' => [
                'controller' => 'Projects',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'project-create' => [
                'controller' => 'ProjectCreate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'project-update' => [
                'controller' => 'ProjectUpdate',
                'settings' => [
                    'ads' => true,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            /* Common routes */
            'index' => [
               'controller' => 'Index',
                'settings' => [
                    'currency_switcher' => true,
                ]
            ],

            'login' => [
                'controller' => 'Login',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_browser_language_detection' => true,
                ]
            ],

            'register' => [
                'controller' => 'Register',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                    'no_browser_language_detection' => true,
                ]
            ],

            'affiliate' => [
                'controller' => 'Affiliate'
            ],

            'pages' => [
                'controller' => 'Pages'
            ],

            'page' => [
                'controller' => 'Page'
            ],

            'blog' => [
                'controller' => 'Blog'
            ],

            'api-documentation' => [
                'controller' => 'ApiDocumentation',
            ],

            'contact' => [
                'controller' => 'Contact',
                'settings' => [
                    'allow_team_access' => false,
                ]
            ],

            'activate-user' => [
                'controller' => 'ActivateUser'
            ],

            'lost-password' => [
                'controller' => 'LostPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                ]
            ],

            'reset-password' => [
                'controller' => 'ResetPassword',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                ]
            ],

            'resend-activation' => [
                'controller' => 'ResendActivation',
                'settings' => [
                    'wrapper' => 'basic_wrapper',
                ]
            ],

            'logout' => [
                'controller' => 'Logout'
            ],

            'not-found' => [
                'controller' => 'NotFound',
            ],

            'account' => [
                'controller' => 'Account',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account-preferences' => [
                'controller' => 'AccountPreferences',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account-plan' => [
                'controller' => 'AccountPlan',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account-redeem-code' => [
                'controller' => 'AccountRedeemCode',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account-payments' => [
                'controller' => 'AccountPayments',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account-logs' => [
                'controller' => 'AccountLogs',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account-api' => [
                'controller' => 'AccountApi',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'account-delete' => [
                'controller' => 'AccountDelete',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'referrals' => [
                'controller' => 'Referrals',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'invoice' => [
                'controller' => 'Invoice',
                'settings' => [
                    'wrapper' => 'invoice/invoice_wrapper',
                ]
            ],

            'plan' => [
               'controller' => 'Plan',
                'settings' => [
                    'currency_switcher' => true,
                ],
            ],

            'pay' => [
                'controller' => 'Pay',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                    'currency_switcher' => true,
                ]
            ],

            'pay-billing' => [
                'controller' => 'PayBilling',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                    'currency_switcher' => true,
                ]
            ],

            'pay-thank-you' => [
                'controller' => 'PayThankYou',
                'settings' => [
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                    'currency_switcher' => true,
                ]
            ],

            'teams-system' => [
                'controller' => 'TeamsSystem',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'teams' => [
                'controller' => 'Teams',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'team-create' => [
                'controller' => 'TeamCreate',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'team-update' => [
                'controller' => 'TeamUpdate',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'team' => [
                'controller' => 'Team',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'teams-members' => [
                'controller' => 'TeamsMembers',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'team-member-create' => [
                'controller' => 'TeamMemberCreate',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'team-member-update' => [
                'controller' => 'TeamMemberUpdate',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'teams-member' => [
                'controller' => 'TeamsMember',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'internal-notifications' => [
                'controller' => 'InternalNotifications',
                'settings' => [
                    'ads' => true,
                    'allow_team_access' => false,
                    'wrapper' => 'app_wrapper',
                ]
            ],

            'push-subscribers' => [
                'controller' => 'PushSubscribers',
                'settings' => [
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'sso' => [
                'controller' => 'SSO',
                'settings' => [
                    'allow_team_access' => false,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            /* Webhooks */
            'webhook-paypal' => [
                'controller' => 'WebhookPaypal',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-stripe' => [
                'controller' => 'WebhookStripe',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-coinbase' => [
                'controller' => 'WebhookCoinbase',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-payu' => [
                'controller' => 'WebhookPayu',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-iyzico' => [
                'controller' => 'WebhookIyzico',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-paystack' => [
                'controller' => 'WebhookPaystack',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-razorpay' => [
                'controller' => 'WebhookRazorpay',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-mollie' => [
                'controller' => 'WebhookMollie',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-yookassa' => [
                'controller' => 'WebhookYookassa',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-crypto-com' => [
                'controller' => 'WebhookCryptoCom',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-paddle' => [
                'controller' => 'WebhookPaddle',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-mercadopago' => [
                'controller' => 'WebhookMercadopago',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-midtrans' => [
                'controller' => 'WebhookMidtrans',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'webhook-flutterwave' => [
                'controller' => 'WebhookFlutterwave',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            /* Others */
            'cookie-consent' => [
                'controller' => 'CookieConsent',
                'settings' => [
                    'no_authentication_check' => true,
                    'no_browser_language_detection' => true,
                ]
            ],

            'sitemap' => [
                'controller' => 'Sitemap',
                'settings' => [
                    'no_authentication_check' => true,
                    'no_browser_language_detection' => true,
                ]
            ],

            'cron' => [
                'controller' => 'Cron',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],

            'broadcast' => [
                'controller' => 'Broadcast',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                    'no_browser_language_detection' => true,
                ]
            ],
        ],

        'api' => [
            'projects' => [
                'controller' => 'ApiProjects',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            /* Common routes */
            'teams' => [
                'controller' => 'ApiTeams',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                ]
            ],
            'teams-member' => [
                'controller' => 'ApiTeamsMember',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                ]
            ],
            'team-members' => [
                'controller' => 'ApiTeamMembers',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false,
                ]
            ],
            'user' => [
                'controller' => 'ApiUser',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'payments' => [
                'controller' => 'ApiPayments',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
            'logs' => [
                'controller' => 'ApiLogs',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
        ],

        /* Admin Panel */
        /* Authentication is set by default to 'admin' */
        'admin' => [
            'chats-assistants' => [
                'controller' => 'AdminChatsAssistants',
            ],

            'chat-assistant-create' => [
                'controller' => 'AdminChatAssistantCreate',
            ],

            'chat-assistant-update' => [
                'controller' => 'AdminChatAssistantUpdate',
            ],

            'templates-categories' => [
                'controller' => 'AdminTemplatesCategories',
            ],

            'template-category-create' => [
                'controller' => 'AdminTemplateCategoryCreate',
            ],

            'template-category-update' => [
                'controller' => 'AdminTemplateCategoryUpdate',
            ],

            'templates' => [
                'controller' => 'AdminTemplates',
            ],

            'template-create' => [
                'controller' => 'AdminTemplateCreate',
            ],

            'template-update' => [
                'controller' => 'AdminTemplateUpdate',
            ],

            'documents' => [
                'controller' => 'AdminDocuments',
            ],

            'images' => [
                'controller' => 'AdminImages',
            ],

            'upscaled-images' => [
                'controller' => 'AdminUpscaledImages',
            ],

            'removed-background-images' => [
                'controller' => 'AdminRemovedBackgroundImages',
            ],

            'replaced-background-images' => [
                'controller' => 'AdminReplacedBackgroundImages',
            ],

            'transcriptions' => [
                'controller' => 'AdminTranscriptions',
            ],

            'syntheses' => [
                'controller' => 'AdminSyntheses',
            ],

            'chats' => [
                'controller' => 'AdminChats',
            ],

            'projects' => [
                'controller' => 'AdminProjects',
            ],

            /* Common routes */
            'index' => [
                'controller' => 'AdminIndex',
            ],

            'users' => [
                'controller' => 'AdminUsers',
            ],

            'user-create' => [
                'controller' => 'AdminUserCreate',
            ],

            'user-view' => [
                'controller' => 'AdminUserView',
            ],

            'user-update' => [
                'controller' => 'AdminUserUpdate',
            ],

            'users-logs' => [
                'controller' => 'AdminUsersLogs',
            ],

            'redeemed-codes' => [
                'controller' => 'AdminRedeemedCodes',
            ],

            'blog-posts' => [
                'controller' => 'AdminBlogPosts'
            ],

            'blog-post-create' => [
                'controller' => 'AdminBlogPostCreate'
            ],

            'blog-post-update' => [
                'controller' => 'AdminBlogPostUpdate'
            ],

            'blog-posts-categories' => [
                'controller' => 'AdminBlogPostsCategories'
            ],

            'blog-posts-category-create' => [
                'controller' => 'AdminBlogPostsCategoryCreate'
            ],

            'blog-posts-category-update' => [
                'controller' => 'AdminBlogPostsCategoryUpdate'
            ],

            'resources' => [
                'controller' => 'AdminResources'
            ],

            'pages' => [
                'controller' => 'AdminPages'
            ],

            'page-create' => [
                'controller' => 'AdminPageCreate'
            ],

            'page-update' => [
                'controller' => 'AdminPageUpdate'
            ],

            'pages-categories' => [
                'controller' => 'AdminPagesCategories'
            ],

            'pages-category-create' => [
                'controller' => 'AdminPagesCategoryCreate'
            ],

            'pages-category-update' => [
                'controller' => 'AdminPagesCategoryUpdate'
            ],

            'plans' => [
                'controller' => 'AdminPlans',
            ],

            'plan-create' => [
                'controller' => 'AdminPlanCreate',
            ],

            'plan-update' => [
                'controller' => 'AdminPlanUpdate',
            ],

            'codes' => [
                'controller' => 'AdminCodes',
            ],

            'code-create' => [
                'controller' => 'AdminCodeCreate',
            ],

            'code-update' => [
                'controller' => 'AdminCodeUpdate',
            ],

            'taxes' => [
                'controller' => 'AdminTaxes',
            ],

            'tax-create' => [
                'controller' => 'AdminTaxCreate',
            ],

            'tax-update' => [
                'controller' => 'AdminTaxUpdate',
            ],

            'affiliates-withdrawals' => [
                'controller' => 'AdminAffiliatesWithdrawals',
            ],

            'payments' => [
                'controller' => 'AdminPayments',
            ],

            'statistics' => [
                'controller' => 'AdminStatistics',
            ],

            'plugins' => [
                'controller' => 'AdminPlugins',
            ],

            'languages' => [
                'controller' => 'AdminLanguages'
            ],

            'language-create' => [
                'controller' => 'AdminLanguageCreate'
            ],

            'language-update' => [
                'controller' => 'AdminLanguageUpdate'
            ],

            'settings' => [
                'controller' => 'AdminSettings',
            ],

            'api-documentation' => [
                'controller' => 'AdminApiDocumentation',
            ],

            'teams' => [
                'controller' => 'AdminTeams',
            ],

            'logs' => [
                'controller' => 'AdminLogs',
            ],

            'log' => [
                'controller' => 'AdminLog',
            ],

            'log-download' => [
                'controller' => 'AdminLogDownload',
                'settings' => [
                    'has_view' => false,
                ]
            ],

            'broadcasts' => [
                'controller' => 'AdminBroadcasts',
            ],

            'broadcast-view' => [
                'controller' => 'AdminBroadcastView',
            ],

            'broadcast-create' => [
                'controller' => 'AdminBroadcastCreate',
            ],

            'broadcast-update' => [
                'controller' => 'AdminBroadcastUpdate',
            ],

            'internal-notifications' => [
                'controller' => 'AdminInternalNotifications',
            ],

            'internal-notification-create' => [
                'controller' => 'AdminInternalNotificationCreate',
            ],

            'push-subscribers' => [
                'controller' => 'AdminPushSubscribers',
            ],

            'push-notifications' => [
                'controller' => 'AdminPushNotifications',
            ],

            'push-notification-create' => [
                'controller' => 'AdminPushNotificationCreate',
            ],

            'push-notification-update' => [
                'controller' => 'AdminPushNotificationUpdate',
            ],
        ],

        'admin-api' => [
            'users' => [
                'controller' => 'AdminApiUsers',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            'plans' => [
                'controller' => 'AdminApiPlans',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],

            'sso' => [
                'controller' => 'AdminApiSSO',
                'settings' => [
                    'no_authentication_check' => true,
                    'has_view' => false
                ]
            ],
        ],
    ];


    public static function parse_url() {

        $params = self::$params;

        if(isset($_GET['altum'])) {
            $params = explode('/', input_clean(rtrim($_GET['altum'], '/')));
        }

        if(php_sapi_name() == 'cli' && isset($_SERVER['argv'])) {
            $params = explode('/', input_clean(rtrim($_SERVER['argv'][1] ?? '', '/')));
            parse_str(implode('&', array_slice($_SERVER['argv'], 2)), $_GET);
        }

        self::$params = $params;

        return $params;

    }

    public static function get_params() {

        return self::$params = array_values(self::$params);
    }

    public static function parse_language() {

        /* Check for potential language set in the first parameter */
        if(!empty(self::$params[0]) && in_array(self::$params[0], Language::$active_languages)) {

            /* Set the language */
            $language_code = input_clean(self::$params[0]);
            Language::set_by_code($language_code);
            self::$language_code = $language_code;

            /* Unset the parameter so that it wont be used further */
            unset(self::$params[0]);
            self::$params = array_values(self::$params);

        }

    }

    public static function parse_controller() {

        self::$original_request = input_clean(implode('/', self::$params));
        self::$original_request_query = http_build_query(array_diff_key($_GET, array_flip(['altum'])));

        /* Check if the current link accessed is actually the original url or not (multi domain use) */
        $original_url_host = parse_url(url(), PHP_URL_HOST);
        $request_url_host = input_clean($_SERVER['HTTP_HOST']);

        /* Check for potential other paths than the default one (admin panel) */
        if(!empty(self::$params[0])) {

            if(in_array(self::$params[0], ['admin', 'admin-api', 'api'])) {
                self::$path = self::$params[0];

                unset(self::$params[0]);

                self::$params = array_values(self::$params);
            }

        }

        if(!empty(self::$params[0])) {

            if(array_key_exists(self::$params[0], self::$routes[self::$path]) && file_exists(APP_PATH . 'controllers/' . (self::$path != '' ? self::$path . '/' : null) . self::$routes[self::$path][self::$params[0]]['controller'] . '.php')) {

                self::$controller_key = self::$params[0];

                unset(self::$params[0]);

            } else {

                /* Not found controller */
                self::$path = '';
                self::$controller_key = 'not-found';

            }

        }

        /* Save the current controller */
        if(!isset(self::$routes[self::$path][self::$controller_key])) {
            /* Not found controller */
            self::$path = '';
            self::$controller_key = 'not-found';
        }
        self::$controller = self::$routes[self::$path][self::$controller_key]['controller'];

        /* Admin path */
        if(self::$path == 'admin' && !isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$routes[self::$path][self::$controller_key]['settings'] = [
                'authentication' => 'admin',
                'allow_team_access' => false,
            ];
        }

        /* Make sure we also save the controller specific settings */
        if(isset(self::$routes[self::$path][self::$controller_key]['settings'])) {
            self::$controller_settings = array_merge(self::$controller_settings, self::$routes[self::$path][self::$controller_key]['settings']);
        }

        return self::$controller;

    }

    public static function get_controller($controller_ame, $path = '') {

        require_once APP_PATH . 'controllers/' . ($path != '' ? $path . '/' : null) . $controller_ame . '.php';

        /* Create a new instance of the controller */
        $class = 'Altum\\Controllers\\' . $controller_ame;

        /* Instantiate the controller class */
        $controller = new $class;

        return $controller;
    }

    public static function parse_method($controller) {

        $method = self::$method;

        /* Start the checks for existing potential methods */
        if(isset(self::get_params()[0])) {

            /* Try to check the methods with prettier URLs */
            self::$params[0] = str_replace('-', '_', self::$params[0]);

            /* Make sure to check the class method if set in the url */
            if(method_exists($controller, self::get_params()[0])) {

                /* Make sure the method is not private */
                $reflection = new \ReflectionMethod($controller, self::get_params()[0]);
                if($reflection->isPublic()) {
                    $method = self::get_params()[0];
                    unset(self::$params[0]);
                }

            }

            /* Restore pretty URL if not used */
            else {
                self::$params[0] = str_replace('_', '-', self::$params[0]);
            }
        }

        return self::$method = $method;

    }

}
