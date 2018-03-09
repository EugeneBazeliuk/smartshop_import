<?php namespace Smartshop\Import;

use Event;
use Backend;
use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Smartshop\Import\Models\Log as ImportLog;

/**
 * Import Plugin Information File
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
            'name'        => 'smartshop.import::lang.plugin.name',
            'description' => 'smartshop.import::lang.plugin.description',
            'author'      => 'Djetson',
            'icon'        => 'icon-leaf',
        ];
    }

    /**
     * Register plugin settings
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'tasks' => [
                'label'       => 'smartshop.import::lang.tasks.title',
                'description' => 'smartshop.import::lang.tasks.description',
                'category'    => 'smartshop.import::lang.plugin.name',
                'icon'        => 'icon-globe',
                'url'         => Backend::url('smartshop/import/tasks'),
                'order'       => 200,
                'permissions' => ['smartshop.import.access_task'],
            ],
            'templates' => [
                'label'       => 'smartshop.import::lang.templates.title',
                'description' => 'smartshop.import::lang.templates.description',
                'category'    => 'smartshop.import::lang.plugin.name',
                'icon'        => 'icon-globe',
                'url'         => Backend::url('smartshop/import/templates'),
                'order'       => 200,
                'permissions' => ['smartshop.import.access_template'],
            ],
            'logs' => [
                'label'       => 'smartshop.import::lang.logs.title',
                'description' => 'smartshop.import::lang.logs.description',
                'category'    => 'smartshop.import::lang.plugin.name',
                'icon'        => 'icon-globe',
                'url'         => Backend::url('smartshop/import/logs'),
                'order'       => 200,
                'permissions' => ['smartshop.import.access_logs'],
            ]
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Apply ProductImport logging
         */
        Event::listen('smartshop.catalog.importRun', function ($results, $path, $user, $template) {
            ImportLog::addRecord($results, $path, $user, $template);
        });
    }
}
