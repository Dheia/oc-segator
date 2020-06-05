<?php namespace Waka\Segator\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateTagsTableU102 extends Migration
{
    public function up()
    {
        Schema::table('waka_segator_tags', function (Blueprint $table) {
            $table->text('only_tag')->nullable();
        });
    }

    public function down()
    {
        Schema::table('waka_segator_tags', function (Blueprint $table) {
            Schema::dropColumn('only_tag');
        });
    }
}