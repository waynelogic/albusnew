<?php namespace Albus\Catalog\Classes\Registration;

// October CMS
use Event;
use Cms\Classes\Page;

// Lovata
use Lovata\Shopaholic\Models\Category;
use Lovata\Shopaholic\Classes\Item\CategoryItem;

use Lovata\Shopaholic\Models\Product;
use Lovata\Shopaholic\Classes\Item\ProductItem;
use Albus\Catalog\Classes\Helpers\CategoryImageByProduct;
use Albus\Catalog\Classes\Event\PaymentMethod\ExtendPaymentMethodFieldsHandler;
use Albus\Catalog\Classes\Event\PaymentMethod\PaymentMethodModelHandler;
trait Boot {
    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        Event::subscribe(ExtendPaymentMethodFieldsHandler::class);
        Event::subscribe(PaymentMethodModelHandler::class);

        Event::listen('cms.pageLookup.listTypes', function() {
            return [
                'shop-category' => 'Shopaholic - Категория магазина',
                'shop-product' => 'Shopaholic - Товар',
            ];
        });
    
        Event::listen('cms.pageLookup.getTypeInfo', function($type) {
            // Shopaholic Cate
            if ($type == 'shop-category') {
                $records = Category::all();
                
                $iterator = function($records) use (&$iterator) {
                    $result = [];
                    foreach ($records as $record) {
                        if (!$record->children) {
                            $result[$record->id] = $record->name;
                        }
                        else {
                            $result[$record->id] = [
                                'title' => $record->name,
                                'items' => $iterator($record->children)
                            ];
                        }
                    }
                    return $result;
                };
                return [
                    'references' => $iterator($records),
                    'cmsPages' => Page::withComponent('CategoryPage')->all()
                ];
            }

            if ($type == 'shop-product') {
                $records = Product::all();
                
                $iterator = function($records) use (&$iterator) {
                    $result = [];
                    foreach ($records as $record) {
                        if (!$record->children) {
                            $result[$record->id] = $record->name;
                        }
                        else {
                            $result[$record->id] = [
                                'title' => $record->name,
                                'items' => $iterator($record->children)
                            ];
                        }
                    }
                    return $result;
                };
                return [
                    'references' => $iterator($records),
                    'cmsPages' => Page::withComponent('ProductPage')->all()
                ];
            }
        });
    
        Event::listen('cms.pageLookup.resolveItem', function($type, $item, $url, $theme) {
            if ($type === 'shop-category') {

                $model = Category::find($item->reference);
                if (!$model) {
                    return;
                }
                
                $pageUrl = CategoryItem::make($model->id)->getPageUrl($item->cmsPage);

                $result = [
                    'url' => $pageUrl,
                    'isActive' => $pageUrl == $url,
                    'title' => $model->name,
                    'mtime' => $model->updated_at,
                ];

                if (!$item->nesting) {
                    return $result;
                }
            }
            if ($type === 'shop-product') {

                $model = Product::find($item->reference);
                if (!$model) {
                    return;
                }
                
                $pageUrl = ProductItem::make($model->id)->getPageUrl($item->cmsPage);

                $result = [
                    'url' => $pageUrl,
                    'isActive' => $pageUrl == $url,
                    'title' => $model->name,
                    'mtime' => $model->updated_at,
                ];

                if (!$item->nesting) {
                    return $result;
                }
            }
        });

        $this->cmsPageFields();

        // $arCategories = new CategoryImageByProduct();
        // $arCategories->checkCategories();
    }

    public function pageFinder()
    {
        # code...
    }

    public function cmsPageFields()
    {
        Event::listen('cms.template.getTemplateToolbarSettingsButtons', function ($extension, $dataHolder) {
            if ($dataHolder->templateType === 'page') {
                $dataHolder->buttons[] = [
                    'button' => 'Иконка',
                    'icon' => 'octo-icon-info',
                    'popupTitle' => 'Иконка',
                    'properties' => [
                        [
                            'property' => 'page_icon',
                            'title' => 'Укажите SVG ID',
                            'type' => 'string'
                        ]
                    ]
                ];
            }
        });
    }

}