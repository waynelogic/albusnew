<?php namespace Albus\Catalog\Classes\Helpers;

use Artisan;
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Models\Product;

class CategoryImageByProduct {
    // $arCategories = Category::orderBy('nest_depth', 'desc')->get();
    // foreach ($arCategories as $obCategory) {
    //     if (!empty($obCategory->product)) {
    //         dd($obCategory);
    //     };
    // }

    public $arCategories;

    public function __construct($arCategories = null)
    {
        if (empty($arCategories)) {
            $this->arCategories = Category::orderBy('nest_depth', 'desc')->get();
        }
    }

    public function checkCategories()
    {
        foreach ($this->arCategories as $obCategory) {
            if (empty($obCategory->preview_image)) {
                
                $obProduct = Product::where('category_id', $obCategory->id)->whereHas('preview_image', function ($query) {
                    $query->whereNotNull('id');
                })->first();

                if (isset($obProduct)) {
                    $obCategory->preview_image()->add($obProduct->preview_image);
                } elseif ($obCategory->children->count() > 0)  {
                    foreach ($obCategory->children as $obChildren) {
                        if (isset($obChildren->preview_image)) {
                            $obCategory->preview_image()->add($obChildren->preview_image);
                            break;
                        }
                    }
                }
            }
        }
        // Artisan::command('cache:clear');
    }

    public function setImage()
    {
        # code...
    }

}