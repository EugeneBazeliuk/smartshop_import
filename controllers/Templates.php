<?php namespace Smartshop\Import\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Smartshop\Import\Models\Template;

/**
 * Templates Back-end Controller
 *
 * @mixin \Backend\Behaviors\FormController
 * @mixin \Backend\Behaviors\ListController
 */
class Templates extends Controller
{
    /**
     * @var array Extensions implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var array FormController configuration.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var array ListController configuration.
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Permissions required to view this page.
     */
    public $requiredPermissions = ['smartshop.import.access_templates'];

    /**
     * Templates constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Smartshop.Import', 'templates');
    }

    /**
     * @param \Backend\Widgets\Form $widget
     * @param array $data
     *
     * @return array
     * @throws \ApplicationException
     */
    public function formExtendRefreshData($widget, $data)
    {
        $model = new Template();

        /** @var \System\Models\File $file */
        $file = $model
            ->file()
            ->withDeferred($widget->getSessionKey())
            ->orderBy('id', 'desc')
            ->first();

        if ($file) {
            array_add($data, 'mapping', $model->getFileMapping($file));
        }

        return $data;
    }
}
