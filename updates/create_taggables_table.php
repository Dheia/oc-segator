<?php namespace Waka\Segator\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateTaggablesTable extends Migration
{
    public function up()
    {
        Schema::create('waka_segator_taggables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('tag_id');
            $table->integer('taggable_id')->unsigned();
            $table->string('taggable_type');
            $table->index(['taggable_id', 'taggable_type'], 'taggable');
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_segator_taggables');
    }
}
