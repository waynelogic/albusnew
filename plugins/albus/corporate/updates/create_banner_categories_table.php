<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateBannerCategoriesTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
return new class extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corporate_banner_categories', function(Blueprint $obTable) {
            $obTable->id();
            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->timestamps();

            $obTable->integer('sort_order')->default(0);
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corporate_banner_categories');
    }
};
