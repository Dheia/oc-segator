<?php namespace Waka\Segator\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateTagsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_segator_tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('is_active')->nullable();
            $table->boolean('is_hidden')->nullable();
            $table->string('data_source');
            $table->boolean('is_manual')->nullable();
            $table->boolean('is_auto_class_calculs')->nullable();
            $table->string('class_calculs')->nullable();
            $table->text('parent_incs')->nullable();
            $table->text('parent_excs')->nullable();
            $table->text('calculs')->nullable();
            //reorder
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_segator_tags');
    }
}