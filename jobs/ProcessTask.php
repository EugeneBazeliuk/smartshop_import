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

    /**
     * ProcessingTask constructor.
     *
     * @param \Smartshop\Import\Models\Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
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

        // Get Import Data
        $data = $this->task->getImportData();

        // Run Import
        $productImport = new ProductImport();
        $productImport->importData($data);

        // Delete task
        $this->task->delete();
    }

    /**
     * Failed handler
     * @param \Exception $ex
     * @return void
     */
    public function failed(Exception $ex)
    {
        $this->task->failed();
    }
}