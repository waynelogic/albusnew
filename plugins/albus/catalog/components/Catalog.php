<?php namespace Albus\Catalog\Components;

use Cms\Classes\ComponentBase;

/**
 * Catalog Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class Catalog extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Каталог',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [];
    }
    
    /**
     * Товар
     * @var mixed
     */
    public $obProductItem;

    /**
     * Текущая активная категория
     * @var mixed
     */
    public $obActiveCategoryItem;

    public function onRun()
    {
        $this->obProductItem = $this->page->ProductPage->get();
        if (!empty($this->obProductItem)) {
            $this->productPage();
        } else {
            $this->catalogPage();
        }
        $this->breadcrumbs();
    }

    /**
     * Получить активную категорию
     *
     * @return void
     */
    public function setActiveCategory()
    {
        $obCategoryItem = $this->page->CategoryPage->get();
        $obMainCategoryItem = $this->page->MainCategoryPage->get();
        $this->obActiveCategoryItem = !empty($obCategoryItem) ? $obCategoryItem : $obMainCategoryItem;
    }
    
    /**
     * Операции для вывода данных каталога - категория, фильтр, и т.д.
     *
     * @return void
     */
    public function catalogPage()
    {
        $this->setActiveCategory();
        $this->page['obActiveCategoryItem'] = $this->obActiveCategoryItem;
        $this->page->title = $this->obActiveCategoryItem->name;

        $sActiveSorting = $this->page->ProductList->getSorting();
        $obProductList = $this->page->ProductList->make()->sort($sActiveSorting)->active()->category($this->obActiveCategoryItem->id, true);
        $this->page['obProductList'] = $obProductList;
    }
    
    /**
     * Операции для вывода данных на страницу товара
     *
     * @return void
     */
    public function productPage()
    {
        $this->setActiveCategory();
        $this->page->title = $this->obProductItem->name;
        $this->page['obProduct'] = $this->obProductItem;
    }

    public function breadcrumbs() {
        $arBreadcrumbs = [];
        if (!empty($this->obProductItem)) {
            $arBreadcrumbs[] = ['title' => $this->obProductItem->name, 'url' => $this->obProductItem->getPageUrl('products/index')];
        }
   
        if (!empty($this->obActiveCategoryItem)) {
            $obCurrentCategory = $this->obActiveCategoryItem;
            while($obCurrentCategory->isNotEmpty()) {
                $arBreadcrumbs[] = ['title' => $obCurrentCategory->name, 'url' => $obCurrentCategory->getPageUrl('products/index', ['slug'])];
                $obCurrentCategory = $obCurrentCategory->parent;
            }
        }
        $arBreadcrumbs[] = ['title' => 'Главная', 'url' => \Cms\Classes\Page::url('home')];
        $arBreadcrumbs = array_reverse($arBreadcrumbs);
        $this->page['arBreadcrumbs'] = $arBreadcrumbs;
    }



    // function onInit() {
    //     $obProductItem = $this->ProductPage->get();
    //     $obBrandItem = $this->BrandPage->get();
    //     $obCategoryItem = $this->CategoryPage->get();
    //     $obMainCategoryItem = $this->MainCategoryPage->get();
    //     if (!empty($this->param('slug')) && empty($obProductItem) && empty($obBrandItem) && empty($obCategoryItem)) {
    //         return $this->ProductPage->getErrorResponse();
    //     }
    
    //     $obActiveCategoryItem = !empty($obCategoryItem) ? $obCategoryItem : $obMainCategoryItem;
    //     $arBreadcrumbs = [];
    //     if (!empty($obProductItem)) {
    //         $arBreadcrumbs[] = ['name' => $obProductItem->name, 'url' => $obProductItem->getPageUrl('catalog')];
    //     }
    
    //     if (!empty($obBrandItem)) {
    //         $arBreadcrumbs[] = ['name' => $obBrandItem->name, 'url' => $obBrandItem->getPageUrl('catalog')];
    //     }
    
    //     if (!empty($obActiveCategoryItem)) {
    //         $obCurrentCategory = $obActiveCategoryItem;
    //         while($obCurrentCategory->isNotEmpty()) {
    //             $arBreadcrumbs[] = ['name' => $obCurrentCategory->name, 'url' => $obCurrentCategory->getPageUrl('catalog', ['slug'])];
    //             $obCurrentCategory = $obCurrentCategory->parent;
    //         }
    //     }
    
    //     $arBreadcrumbs[] = ['name' => 'Home', 'url' => \Cms\Classes\Page::url('index')];
    //     $arBreadcrumbs = array_reverse($arBreadcrumbs);
    
    //     $this['obProduct'] = $obProductItem;
    //     $this['obBrand'] = $obBrandItem;
    //     $this['obActiveCategory'] = $obActiveCategoryItem;
    //     $this['arBreadcrumbs'] = $arBreadcrumbs;
    // }
}
