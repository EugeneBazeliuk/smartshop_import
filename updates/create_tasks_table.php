<?php namespace Smartshop\Import\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('smartshop_import_tasks', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            // Base
            $table->increments('id');
            $table->string('status')->nullable();
            // BelongTo Author
            $table->integer('author_id')->unsigned()->nullable();
            // BelongTo Template
            $table->integer('template_id')->unsigned()->nullable();
            // Timestamps
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('smartshop_import_tasks');
    }
}
