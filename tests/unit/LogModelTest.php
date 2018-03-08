<?php namespace SmartShop\Import\Tests;

use PluginTestCase;
use System\Models\File as FileModel;
use SmartShop\Import\Models\Log;

/**
 * Class LogModelTest
 */
class LogModelTest extends PluginTestCase
{
    public static $log = [
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
     * Creation test
     */
    public function test_create_log()
    {
        Log::truncate();

        $model = new Log();
        $model->fill(self::$log);
        $model->file()->add($this->getImportFile());
        $model->save();

        // Assert model id
        $this->assertEquals(1, $model->id);

        // Assert model attributes
        foreach (self::$log as $key => $val) {
            $this->assertEquals($val, $model->{$key});
        }

        $this->assertEquals(0, $model->createdCount);
        $this->assertEquals(0, $model->updatedCount);
        $this->assertEquals(1, $model->errorCount);
        $this->assertEquals(0, $model->warningCount);
        $this->assertEquals(0, $model->skipperCount);
    }

    /**
     * Get Import File
     */
    private function getImportFile()
    {
        $file = new FileModel();
        $file->fromFile(plugins_path().'/smartshop/import/tests/fixtures/file.xml');

        return $file;
    }
}