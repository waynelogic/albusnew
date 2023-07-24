<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateDepartmentsTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreateDepartmentsTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corp_departments', function(Blueprint $obTable) {
            $obTable->id();
            
            $obTable->boolean('active')->default(1);
            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->text('phone')->nullable();
            $obTable->text('email')->nullable();
            $obTable->string('address')->nullable();
            $obTable->string('coordinates')->nullable();

            $obTable->text('preview_text')->nullable();
            $obTable->text('description')->nullable();
            $obTable->string('external_id')->nullable();
            $obTable->integer('parent_id')->nullable()->unsigned();
            $obTable->integer('nest_left')->nullable()->unsigned();
            $obTable->integer('nest_right')->nullable()->unsigned();
            $obTable->integer('nest_depth')->nullable()->unsigned();
            $obTable->timestamps();

            $obTable->index('name');
            $obTable->index('external_id');
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corp_departments');
    }
}
