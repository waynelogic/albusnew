<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateProjectsTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreateProjectsTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corp_projects', function(Blueprint $obTable) {
            $obTable->id();
            $obTable->timestamps();

            $obTable->boolean('active')->default(1);
            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->integer('partner_id')->nullable()->unsigned();
            $obTable->integer('servicecategory_id')->nullable()->unsigned();
            $obTable->string('external_id')->nullable();
            $obTable->text('preview_text')->nullable();
            $obTable->text('content')->nullable();

            $obTable->string('sphere')->nullable();
            $obTable->timestamp('published_at')->nullable();
            $obTable->boolean('published')->default(false);
            
            $obTable->index('name');
            $obTable->index('external_id');
            $obTable->index('partner_id');
            $obTable->index('servicecategory_id');
        });

        Schema::create('albus_corp_project_service', function($obTable)
        {
            $obTable->integer('project_id')->unsigned();
            $obTable->integer('service_id')->unsigned();
            $obTable->primary(['project_id', 'service_id']);
            $obTable->integer('sort_order')->nullable();
        });

        Schema::create('albus_corp_project_employee', function($obTable)
        {
            $obTable->integer('project_id')->unsigned();
            $obTable->integer('employee_id')->unsigned();
            $obTable->primary(['project_id', 'employee_id']);
            $obTable->integer('sort_order')->nullable();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corp_projects');
        Schema::dropIfExists('albus_corp_project_service');
        Schema::dropIfExists('albus_corp_project_employee');
    }
}
