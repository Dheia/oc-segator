<?php namespace Waka\Segator;

use Backend;
use Lang;
use System\Classes\PluginBase;

/**
 * Segator Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Segator',
            'description' => 'No description provided yet...',
            'author' => 'waka',
            'icon' => 'icon-leaf',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    public function registerFormWidgets(): array
    {
        return [
            'Waka\Segator\FormWidgets\TagsList' => 'tagslist',
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        \Event::listen('backend.top.index', function ($controller) {
            $user = \BackendAuth::getUser();
            if (!$user->hasAccess('waka.segator.admin')) {
                return;
            }
            if (in_array('Waka.Segator.Behaviors.CalculTags', $controller->implement)) {
                $data = [
                    'model' => $modelClass = str_replace('\\', '\\\\', $controller->listGetConfig()->modelClass),
                    //'modelId' => $controller->formGetModel()->id
                ];
                return \View::make('waka.segator::buttonIndexTags')->withData($data);;
            }
        });
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Waka\Segator\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'waka.segator.admin.super' => [
                'tab' => 'Waka - Segmentation',
                'label' => "Super administrateur de l'outil de segmentation",
            ],
            'waka.segator.admin' => [
                'tab' => 'Waka - Segmentation',
                'label' => "Administrateur de l'outil de segmentation",
            ],
            'waka.segator.user' => [
                'tab' => 'Waka - Segmentation',
                'label' => "Utilisateur de l'outil de segmentation",
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [];
    }

    public function registerSettings()
    {
        return [
            'tags' => [
                'label' => Lang::get('waka.segator::lang.menu.label'),
                'description' => Lang::get('waka.segator::lang.menu.description'),
                'category' => Lang::get('waka.utils::lang.menu.settings_category_model'),
                'icon' => 'icon-filter',
                'permissions' => ['waka.segator.admin'],
                'url' => Backend::url('waka/segator/tags'),
                'order' => 180,
            ],
        ];
    }
}
