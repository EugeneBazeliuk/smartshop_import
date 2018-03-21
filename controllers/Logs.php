<?php namespace Smartshop\Import\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use SmartShop\Import\Models\Log;

/**
 * Logs Back-end Controller
 *
 * @mixin \Backend\Behaviors\ListController
 */
class Logs extends Controller
{
    /**
     * @var array Extensions implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var array ListController configuration.
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var array Permissions required to view this page.
     */
    public $requiredPermissions = ['smartshop.import.access_logs'];

    /**
     * Logs constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Smartshop.Import', 'logs');
    }

    /**
     * On view results Handler
     *
     * @return mixed
     * @throws \ApplicationException
     * @throws \SystemException
     */
    public function onLoadViewResults()
    {
        if (!$model = Log::find(post('record_id'))) {
            throw new \ApplicationException('Model does not found');
        }

        $this->vars['results'] = $model->getResults();

        return $this->makePartial('modal_results');
    }
}
