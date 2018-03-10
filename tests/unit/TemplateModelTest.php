<?php namespace SmartShop\Import\Tests;

use PluginTestCase;
use System\Models\File as FileModel;
use SmartShop\Import\Models\Template;

/**
 * Class TemplateModelTest
 */
class TemplateModelTest extends PluginTestCase
{
    public static $template = [
        // Base
        'name' => 'Test Name',
        'description' => 'Test Description',
    ];

    public $mapping = [
        ['file_column' => 'ID', 'db_column' => 'id'],
        ['file_column' => 'Article', 'db_column' => 'sku'],
        ['file_column' => 'Name', 'db_column' => 'title'],
        ['file_column' => 'Artist', 'db_column' => 'bindings'],
        ['file_column' => 'Translater', 'db_column' => 'bindings'],
    ];

    /**
     * Creation test
     */
    public function test_create_template()
    {
        Template::truncate();

        $model = Template::make(self::$template);
        $model->updateMapping($this->getImportFile());
        $model->save();

        // Assert model id
        $this->assertEquals(1, $model->id);

        // Assert model attributes
        foreach (self::$template as $key => $val) {
            $this->assertEquals($val, $model->{$key});
        }

        // Assert mapping
        $this->assertEquals(5, count($model->mapping));
        $this->assertTrue($this->checkMapping($model->mapping));
    }

    public function test_import_data()
    {
        $model = Template::make(self::$template);
        $model->mapping = $this->mapping;
        $model->save();
    }

    /**
     * Check Mapping
     *
     * @param $fileMapping
     * @return bool
     */
    private function checkMapping($fileMapping)
    {
        foreach ($this->mapping as $map) {
            foreach ($fileMapping as $index => $row) {
                if ($map['file_column'] === $row['file_column']) {
                    unset($fileMapping[$index]);
                    continue;
                }
            }
        }

        return empty($fileMapping) ? true : false;
    }

    /**
     * Get import File
     *
     * @return \System\Models\File
     */
    private function getImportFile()
    {
        $file = new FileModel();
        $file->fromFile(plugins_path().'/smartshop/import/tests/fixtures/file.xml');

        return $file;
    }
}