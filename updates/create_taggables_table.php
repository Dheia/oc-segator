<?php namespace Waka\Segator\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateTaggablesTable extends Migration
{
    public function up()
    {
        Schema::create('waka_segator_taggables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('taggable_id')->unsigned()->nullable();
            $table->string('taggable_type')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_segator_taggables');
    }
}