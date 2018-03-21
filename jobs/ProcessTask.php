<?php namespace SmartShop\Import\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Smartshop\Import\Models\Task;
use SmartShop\Catalog\Models\ProductImport;

class ProcessTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    protected $sessionKey;

    /**
     * ProcessingTask constructor.
     *
     * @param \Smartshop\Import\Models\Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->sessionKey = uniqid();
    }

    /**
     * Fire handler
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        // Mark Task as in process
        $this->task->processing();

        // Run Import
        $productImport = new ProductImport();
        $productImport->setImportTemplate($this->task->template);
        $productImport->import_file()->add($this->task->file, $this->sessionKey);
        $productImport->importData($this->task->getImportData(), $this->sessionKey);

        // Delete task
        $this->task->done();
    }

    /**
     * Failed handler
     * @param \Exception $ex
     * @return void
     * @todo add error message to Task
     */
    public function failed(Exception $ex)
    {
        $this->task->failed();
    }
}