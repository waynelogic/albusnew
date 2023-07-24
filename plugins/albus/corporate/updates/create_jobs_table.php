<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateJobsTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreateJobsTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corp_jobs', function(Blueprint $obTable) {
            $obTable->id();

            $obTable->string('name');
            $obTable->string('slug')->unique();

            $obTable->string('salary')->nullable();
            $obTable->string('experience')->nullable();
            $obTable->string('type')->nullable();
            $obTable->text('preview_text')->nullable();

            $obTable->text('content')->nullable();

            $obTable->integer('department_id')->nullable();

            $obTable->timestamps();
            $obTable->timestamp('published_at')->nullable();
            $obTable->boolean('active')->default(1);
            $obTable->integer('sort_order')->default(0);
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corp_jobs');
    }
}