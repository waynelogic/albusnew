<?php namespace Albus\Corporate\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateServicesTable Migration
 *
 * @link https://docs.octobercms.com/3.x/extend/database/structure.html
 */
class CreateServicesTable extends Migration
{
    /**
     * up builds the migration
     */
    public function up()
    {
        Schema::create('albus_corp_services', function(Blueprint $obTable) {
            $obTable->id();

            $obTable->boolean('active')->default(1);
            $obTable->integer('product_id')->unsigned()->nullable();
            $obTable->string('external_id')->nullable();
            $obTable->integer('category_id')->unsigned()->nullable();

            $obTable->string('name');
            $obTable->string('slug')->unique();
            $obTable->text('preview_text')->nullable();
            $obTable->text('description')->nullable();
            $obTable->text('content')->nullable();

            $obTable->string('deadline')->nullable();
            $obTable->string('price_type')->nullable();
            $obTable->decimal('price', 15, 2)->nullable();
            $obTable->decimal('old_price', 15, 2)->nullable();
            $obTable->integer('sort_order')->default(0);
            $obTable->timestamps();

            $obTable->index('name');
            $obTable->index('external_id');
            $obTable->index('category_id');
            $obTable->index('product_id');
            $obTable->index('price');
            $obTable->index('old_price');
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_corp_services');
    }
}
