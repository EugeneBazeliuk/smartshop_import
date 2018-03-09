<?php namespace Smartshop\Import\Controllers;

use Flash;
use Exception;
use BackendAuth;
use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Smartshop\Import\Models\Task;
use SmartShop\Import\Jobs\ProcessTask;

/**
 * Tasks Back-end Controller
 *
 * @mixin \Backend\Behaviors\ListController
 */
class Tasks extends Controller
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
    public $requiredPermissions = ['smartshop.import.access_tasks'];

    /**
     * @var \Backend\Widgets\Form
     */
    protected $createTaskFormWidget;

    /**
     * Tasks constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Smartshop.Import', 'tasks');
        $this->createTaskFormWidget = $this->createTaskFormWidget();
    }

    /**
     * @return mixed
     * @throws \SystemException
     */
    public function index_onLoadCreateTaskForm()
    {
        $this->vars['createTaskWidget'] = $this->createTaskFormWidget;
        return $this->makePartial('form_create_task');
    }

    /**
     * Create Task Form Widget
     *
     * @return \Backend\Classes\WidgetBase
     * @throws \SystemException
     */
    private function createTaskFormWidget()
    {
        $config = $this->makeConfig('$/smartshop/import/models/task/fields.yaml');
        $config->alias = 'importTaskForm';
        $config->arrayName = 'ImportTask';
        $config->context = 'create';
        $config->model = new Task;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();

        return $widget;
    }

    public function index_onCreateTask()
    {
        try {
            $data = $this->createTaskFormWidget->getSaveData();
            $model = new Task();
            $model->fill($data);
            $model->author = BackendAuth::getUser();
            $model->save(null, $this->createTaskFormWidget->getSessionKey());
        }
        catch (Exception $ex) {
            Flash::error($ex->getMessage());
        }

        ProcessTask::dispatch($model);

        return $this->listRefresh();
    }
}
