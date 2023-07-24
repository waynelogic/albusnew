<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateBannersTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreateBannersTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corp_banners', function(Blueprint $obTable) {
            $obTable->id();

            $obTable->boolean('active')->default(1);
            $obTable->timestamp('published_at')->nullable();
            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->text('title')->nullable();
            $obTable->text('subtitle')->nullable();
            $obTable->text('text')->nullable();

            $obTable->string('theme')->nullable();
            $obTable->text('buttons')->nullable();
            $obTable->timestamps();
            
            $obTable->integer('category_id')->nullable();
            $obTable->integer('sort_order')->default(0);
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corp_banners');
    }
}
