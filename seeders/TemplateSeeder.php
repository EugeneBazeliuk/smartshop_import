<?php namespace SmartShop\Import\Seeders;

use SmartShop\Import\Models\Template;

class TemplateSeeder extends \October\Rain\Database\Updates\Seeder
{
    protected $mapping = [
        'Name' => 'name',
        'Article' => 'sku',
        'isbn' => 'isbn',
        'Cost' => 'price',
        'comment' => 'description',
        'ves' => 'weight',
        // Bindings
        'author' => 'bindings',
        'Artist' => 'bindings',
        'Translater' => 'bindings',
        'Editor' => 'bindings',
        // Categories
        'NameGrpL1' => 'categories',
        'NameGrpL2' => 'categories',
        'NameGrpL3' => 'categories',
        // Properties
        'pagecount' => 'properties',
        'NameCover' => 'properties',
        'NameFormatBook' => 'properties',
        // Publisher
        'NamePublisher' => 'publisher',
        // Publisher Set
        'NameSeria' => 'publisher_set',
    ];

    protected $template = [
        // Base
        'name' => 'First template',
        'description' => 'Test Description',
    ];

    /**
     * Run Seeder
     */
    public function run()
    {
        $template = new Template();
        $template->fill($this->template);
        $template->mapping = $this->getMapping($this->mapping);
        $template->save();
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getMapping($data)
    {
        $mapping = [];

        foreach ($data as $key => $val) {

            $mapping[] = [
                'file_column' => $key,
                'file_value' => '',
                'db_column' => $val,
            ];
        }

        return $mapping;
    }
}