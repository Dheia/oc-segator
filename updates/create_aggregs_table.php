<?php namespace Waka\Segator\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateAggregsTable extends Migration
{
    public function up()
    {
        Schema::create('waka_segator_aggregs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->string('type');
            $table->integer('year');
            $table->integer('month');
            $table->integer('week');
            $table->integer('value');
            $table->integer('data');
            $table->integer('agreggable_id')->unsigned()->nullable();
            $table->string('agressable_type')->nullable();
                                                
            $table->softDeletes();
                        
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waka_segator_aggregs');
    }
}