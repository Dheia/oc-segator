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
            $table->string('data_source')->nullable();
            $table->boolean('auto_class_calculs')->default(true);
            $table->string('class_calculs')->nullable();
            $table->text('calculs')->nullable();
            $table->boolean('is_hidden')->default(false);
            $table->boolean('is_active')->default(true);

            $table->integer('sort_order')->default(0);

            $table->softDeletes();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_segator_tags');
    }
}
