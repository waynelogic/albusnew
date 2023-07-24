<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateEmployeesTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreateEmployeesTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corp_employees', function(Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->boolean('active')->default(1);
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('post')->nullable();
            $table->text('preview_text')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('sort_order')->default(0);
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corp_employees');
    }
}
