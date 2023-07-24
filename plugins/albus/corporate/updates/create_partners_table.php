<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreatePartnersTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreatePartnersTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corp_partners', function(Blueprint $obTable) {
            $obTable->id();
            $obTable->timestamps();

            $obTable->boolean('active')->default(1);
            $obTable->string('name');
            $obTable->string('legal_name');
            $obTable->string('slug')->unique();
            $obTable->string('relations')->nullable();
            $obTable->string('type')->nullable();
            $obTable->string('website')->nullable();


            $obTable->text('preview_text')->nullable();
            $obTable->text('description')->nullable();

            $obTable->integer('department_id')->nullable();
            $obTable->integer('sort_order')->default(0);
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corp_partners');
    }
}
