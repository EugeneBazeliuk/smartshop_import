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
        'TestFieldOne' => 'Test value 1',
        'TestFieldTwo' => 'Test value 2',
    ];

    /**
     * Creation test
     */
    public function test_create_template()
    {
        Template::truncate();

        $file = $this->getImportFile();

        $model = new Template();
        $model->fill(TemplateModelTest::$template);
        $model->file()->add($file);
        $model->updateMapping($file);
        $model->save();

        // Assert model id
        $this->assertEquals(1, $model->id);

        // Assert model attributes
        foreach (self::$template as $key => $val) {
            $this->assertEquals($val, $model->{$key});
        }

        // Assert mapping
        $this->assertEquals(2, count($model->mapping));

        foreach ($model->mapping as $row) {
            $this->assertEquals($row['file_value'], $this->mapping[$row['file_column']]);
        }
    }

    /**
     *
     */
    private function getImportFile()
    {
        $file = new FileModel();
        $file->fromFile(plugins_path().'/smartshop/import/tests/fixtures/file.xml');

        return $file;
    }
}