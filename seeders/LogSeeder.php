<?php namespace SmartShop\Import\Seeders;

use SmartShop\Import\Models\Log;

class LogSeeder extends \October\Rain\Database\Updates\Seeder
{
    protected $log = [
        'details' => [
            'updated' => 0,
            'created' => 0,
            'errors' => [
                0 => 'Test error'
            ],
            'warnings' => [],
            'skipped' => [],
            'errorCount' => 1,
            'warningCount' => 0,
            'skipperCount' => 0,
            'hasMessages' => true
        ],
    ];

    /**
     * Run Seeder
     */
    public function run()
    {
        $template = new Log;
        $template->fill($this->log);

        $template->save();
    }
}