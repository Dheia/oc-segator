<?php namespace Waka\Segator\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateTaggablesTableU103 extends Migration
{
    public function up()
    {
        Schema::table('waka_segator_taggables', function (Blueprint $table) {
            $table->index(['taggable_id', 'taggable_type'], 'taggable');
        });
    }

    public function down()
    {
        Schema::table('waka_segator_taggables', function (Blueprint $table) {
            $table->dropIndex('taggable');
        });
    }
}
