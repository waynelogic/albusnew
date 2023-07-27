<?php namespace Albus\Location\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * CreateCountriesTable Migration
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
        Schema::create('albus_location_countries', function(Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('is_enabled')->default(false);
            $table->string('name')->index();
            $table->string('code');
            $table->integer('geonames_id')->nullable();
            $table->string('currency')->nullable();
        });
    }

    /**
     * down reverses the migration
     */
    public function down()
    {
        Schema::dropIfExists('albus_location_countries');
    }
};
