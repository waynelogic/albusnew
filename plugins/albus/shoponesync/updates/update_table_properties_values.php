<?php namespace Lovata\Shopaholic\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class UpdateTableOffersAddDimensionsField
 * @package Lovata\Shopaholic\Updates
 */
class UpdateTableOffersAddDimensionsField extends Migration
{
    const TABLE_NAME = 'lovata_properties_shopaholic_values';

    /**
     * Apply migration
     */
    public function up()
    {
        if (!Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        $arNewFieldList = [
            'external_id',
        ];

        foreach ($arNewFieldList as $iKey => $sFieldName) {
            if (Schema::hasColumn(self::TABLE_NAME, $sFieldName)) {
                unset($arNewFieldList[$iKey]);
            }
        }

        if (empty($arNewFieldList)) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) use ($arNewFieldList) {
            if (in_array('external_id', $arNewFieldList)) {
                $obTable->string('external_id')->nullable();
            }
        });
    }

    /**
     * Rollback migration
     */
    public function down()
    {
        if (!Schema::hasTable(self::TABLE_NAME)) {
            return;
        }

        $arNewFieldList = [
            'external_id',
        ];

        foreach ($arNewFieldList as $iKey => $sFieldName) {
            if (!Schema::hasColumn(self::TABLE_NAME, $sFieldName)) {
                unset($arNewFieldList[$iKey]);
            }
        }

        if (empty($arNewFieldList)) {
            return;
        }

        Schema::table(self::TABLE_NAME, function (Blueprint $obTable) use ($arNewFieldList) {
            $obTable->dropColumn($arNewFieldList);
        });
    }
}
