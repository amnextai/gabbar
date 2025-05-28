<?php defined('ALTUMCODE') || die() ?>

<div class="app-sidebar">
    <div class="app-sidebar-title text-truncate">
        <a
                href="<?= url() ?>"
                class="h3 m-0 text-decoration-none text-truncate"
                data-logo
                data-light-value="<?= settings()->main->logo_light != '' ? \Altum\Uploads::get_full_url('logo_light') . settings()->main->logo_light : settings()->main->title ?>"
                data-light-class="<?= settings()->main->logo_light != '' ? 'img-fluid navbar-logo' : 'text-truncate' ?>"
                data-light-tag="<?= settings()->main->logo_light != '' ? 'img' : 'div' ?>"
                data-dark-value="<?= settings()->main->logo_dark != '' ? \Altum\Uploads::get_full_url('logo_dark') . settings()->main->logo_dark : settings()->main->title ?>"
                data-dark-class="<?= settings()->main->logo_dark != '' ? 'img-fluid navbar-logo' : 'text-truncate' ?>"
                data-dark-tag="<?= settings()->main->logo_dark != '' ? 'img' : 'div' ?>"
        >
            <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                <img src="<?= \Altum\Uploads::get_full_url('logo_' . \Altum\ThemeStyle::get()) . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} ?>" class="img-fluid navbar-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <div class="text-truncate"><?= settings()->main->title ?></div>
            <?php endif ?>
        </a>
    </div>

    <div class="app-sidebar-links-wrapper d-flex flex-column flex-grow-1 justify-content-between">
        <ul class="app-sidebar-links">
            <li class="<?= \Altum\Router::$controller == 'Dashboard' ? 'active' : null ?> d-flex dropdown" id="internal_notifications">
                <a href="<?= url('dashboard') ?>"><i class="fas fa-fw fa-sm fa-th mr-2"></i> <?= l('dashboard.menu') ?></a>

                <?php if(settings()->internal_notifications->users_is_enabled): ?>
                    <a id="internal_notifications_link" href="#" class="default w-auto dropdown-toggle dropdown-toggle-simple ml-1" data-internal-notifications="user" data-tooltip data-tooltip-hide-on-click title="<?= l('internal_notifications.menu') ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="window">
                        <span id="internal_notifications_icon_wrapper" class="fa-layers fa-fw">
                            <i class="fas fa-fw fa-bell"></i>
                            <?php if($this->user->has_pending_internal_notifications): ?>
                            <span class="fa-layers-counter text-danger internal-notification-icon">&nbsp;</span>
                            <?php endif ?>
                        </span>
                    </a>

                    <div id="internal_notifications_content" class="dropdown-menu dropdown-menu-right px-4 py-2" style="width: 550px;max-width: 550px;"></div>

                    <?php include_view(THEME_PATH . 'views/partials/internal_notifications_js.php', ['has_pending_internal_notifications' => $this->user->has_pending_internal_notifications]) ?>
                <?php endif ?>
            </li>

            <?php if(settings()->aix->documents_is_enabled): ?>
                <li class="<?= \Altum\Router::$controller == 'Templates' ? 'active' : null ?>">
                    <a href="<?= url('templates') ?>"><i class="fas fa-fw fa-sm fa-moon mr-2"></i> <?= l('templates.menu') ?></a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['Documents', 'DocumentUpdate', 'DocumentCreate']) ? 'active' : null ?>">
                    <a href="<?= url('documents') ?>"><i class="fas fa-fw fa-sm fa-robot mr-2"></i> <?= l('documents.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->aix->images_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['Images', 'ImageUpdate', 'ImageCreate']) ? 'active' : null ?>">
                    <a href="<?= url('images') ?>"><i class="fas fa-fw fa-sm fa-icons mr-2"></i> <?= l('images.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->aix->upscaled_images_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['UpscaledImages', 'UpscaledImageUpdate', 'UpscaledImageCreate']) ? 'active' : null ?>">
                    <a href="<?= url('upscaled-images') ?>"><i class="fas fa-fw fa-sm fa-expand-arrows-alt mr-2"></i> <?= l('upscaled_images.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->aix->removed_background_images_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['RemovedBackgroundImages', 'RemovedBackgroundImageUpdate', 'RemovedBackgroundImageCreate']) ? 'active' : null ?>">
                    <a href="<?= url('removed-background-images') ?>"><i class="fas fa-fw fa-sm fa-image-portrait mr-2"></i> <?= l('removed_background_images.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->aix->replaced_background_images_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['ReplacedBackgroundImages', 'ReplacedBackgroundImageUpdate', 'ReplacedBackgroundImageCreate']) ? 'active' : null ?>">
                    <a href="<?= url('replaced-background-images') ?>"><i class="fas fa-fw fa-sm fa-photo-film mr-2"></i> <?= l('replaced_background_images.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->aix->chats_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['Chats', 'Chat', 'ChatCreate']) ? 'active' : null ?>">
                    <a href="<?= url('chats') ?>"><i class="fas fa-fw fa-sm fa-comments mr-2"></i> <?= l('chats.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->aix->transcriptions_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['Transcriptions', 'TranscriptionUpdate', 'TranscriptionCreate']) ? 'active' : null ?>">
                    <a href="<?= url('transcriptions') ?>"><i class="fas fa-fw fa-sm fa-microphone-alt mr-2"></i> <?= l('transcriptions.menu') ?></a>
                </li>
            <?php endif ?>

            <?php if(settings()->aix->syntheses_is_enabled): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['Syntheses', 'SynthesisUpdate', 'SynthesisCreate']) ? 'active' : null ?>">
                    <a href="<?= url('syntheses') ?>"><i class="fas fa-fw fa-sm fa-voicemail mr-2"></i> <?= l('syntheses.menu') ?></a>
                </li>
            <?php endif ?>

            <li class="<?= in_array(\Altum\Router::$controller, ['Projects', 'ProjectUpdate', 'ProjectCreate']) ? 'active' : null ?>">
                <a href="<?= url('projects') ?>"><i class="fas fa-fw fa-sm fa-project-diagram mr-2"></i> <?= l('projects.menu') ?></a>
            </li>

            <?php foreach($data->pages as $page): ?>
                <li>
                    <a href="<?= $page->url ?>" target="<?= $page->target ?>">
                        <?php if($page->icon): ?>
                            <i class="<?= $page->icon ?> fa-fw fa-sm mr-2"></i>
                        <?php endif ?>

                        <?= $page->title ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>

        <ul class="app-sidebar-links dropdown">
            <li>
                <a href="#" class="dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="d-flex align-items-center app-sidebar-footer-block">
                        <img src="<?= get_gravatar($this->user->email) ?>" class="app-sidebar-avatar mr-3" loading="lazy" />

                        <div class="app-sidebar-footer-text d-flex flex-column text-truncate">
                            <span class="text-truncate"><?= $this->user->name ?></span>
                            <small class="text-truncate"><?= $this->user->email ?></small>
                        </div>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <?php if(!\Altum\Teams::is_delegated()): ?>
                        <?php if(\Altum\Authentication::is_admin()): ?>
                            <a class="dropdown-item" href="<?= url('admin') ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-primary mr-2"></i> <?= l('global.menu.admin') ?></a>
                            <div class="dropdown-divider"></div>
                        <?php endif ?>

                        <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['Account']) ? 'active' : null ?>" href="<?= url('account') ?>"><i class="fas fa-fw fa-sm fa-user-cog mr-2"></i> <?= l('account.menu') ?></a>

                        <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountPreferences']) ? 'active' : null ?>" href="<?= url('account-preferences') ?>"><i class="fas fa-fw fa-sm fa-sliders-h mr-2"></i> <?= l('account_preferences.menu') ?></a>

                        <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountPlan']) ? 'active' : null ?>" href="<?= url('account-plan') ?>"><i class="fas fa-fw fa-sm fa-box-open mr-2"></i> <?= l('account_plan.menu') ?></a>

                        <?php if(settings()->payment->is_enabled): ?>
                            <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountPayments']) ? 'active' : null ?>" href="<?= url('account-payments') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('account_payments.menu') ?></a>

                            <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                                <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['Referrals']) ? 'active' : null ?>" href="<?= url('referrals') ?>"><i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('referrals.menu') ?></a>
                            <?php endif ?>
                        <?php endif ?>

                        <?php if(settings()->main->api_is_enabled): ?>
                            <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountApi']) ? 'active' : null ?>" href="<?= url('account-api') ?>"><i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('account_api.menu') ?></a>
                        <?php endif ?>

                        <?php if(\Altum\Plugin::is_active('teams')): ?>
                            <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['TeamsSystem', 'Teams', 'Team', 'TeamCreate', 'TeamUpdate', 'TeamsMember', 'TeamsMembers', 'TeamsMemberCreate', 'TeamsMemberUpdate']) ? 'active' : null ?>" href="<?= url('teams-system') ?>"><i class="fas fa-fw fa-sm fa-user-shield mr-2"></i> <?= l('teams_system.menu') ?></a>
                        <?php endif ?>

                        <?php if(settings()->sso->is_enabled && settings()->sso->display_menu_items && count((array) settings()->sso->websites)): ?>
                            <div class="dropdown-divider"></div>

                            <?php foreach(settings()->sso->websites as $website): ?>
                                <a class="dropdown-item" href="<?= url('sso/switch?to=' . $website->id) ?>"><i class="<?= $website->icon ?> fa-fw fa-sm mr-2"></i> <?= sprintf(l('sso.menu'), $website->name) ?></a>
                            <?php endforeach ?>
                        <?php endif ?>
                    <?php endif ?>

                    <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fas fa-fw fa-sm fa-sign-out-alt mr-2"></i> <?= l('global.menu.logout') ?></a>
                </div>
            </li>
        </ul>
    </div>
</div>

<?php ob_start() ?>
<script>
    document.querySelector('ul[class="app-sidebar-links"] li.active') && document.querySelector('ul[class="app-sidebar-links"] li.active').scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
