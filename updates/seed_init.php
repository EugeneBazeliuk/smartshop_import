<?php namespace SmartShop\Import\Updates;

use App;

/**
 * Class SeedInitial
 * @package Djetson\Shop\Updates
 */
class SeedInitial extends \October\Rain\Database\Updates\Seeder
{
    public function run()
    {
        if (App::environment() !== 'testing') {
            $this->call(\SmartShop\Import\Seeders\TemplateSeeder::class);
        }
    }
}