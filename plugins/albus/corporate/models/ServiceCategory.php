<?php namespace Albus\Corporate\Models;

use Model;
use System\Models\File;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;

/**
 * ServiceCategory Model
 *
 * @link https://docs.octobercms.com/3.x/extend/system/models.html
 */
class ServiceCategory extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\NestedTree;

    /**
     * @var string table name
     */
    public $table = 'albus_corp_service_categories';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array Generate slugs for these attributes.
     */
    protected $slugs = ['slug' => 'name'];

    /**
     * File Attachments
     *
     * @var array
     */
    public $attachOne = [
        'cover' => File::class,
        'icon_img' => File::class
    ];

    public static function getMenuTypeInfo($type)
    {
        $result = [];
        if ($type == 'service-category') {
            $result = [
                'references'   => self::lists('name', 'id'),
                'nesting'      => true,
                'dynamicItems' => true
            ];
        }
        if ($type == 'all-service-categories') {
            $result = [
                'dynamicItems' => true
            ];
        }
        if ($result) {
            $theme = Theme::getActiveTheme();
            $pages = CmsPage::listInTheme($theme, true);
            $cmsPages = [];
            foreach ($pages as $page) {
                if ($page->hasComponent('ServiceCategoryPage')) {
                    $cmsPages[] = $page;
                }
            }
            $result['cmsPages'] = $cmsPages;
        }
        return $result;
    }


    public static function resolveMenuItem($item, $url, $theme)
    {
        $pageName = $item->cmsPage;
        $cmsPage = \Cms\Classes\Page::loadCached($theme, $pageName);
        $items   = self
            ::get()
            ->map(function (self $item) use ($cmsPage, $url, $pageName) {
                // dd($cmsPage);
                // $pageUrl = $cmsPage->url($pageName, ['category' => $item->slug]);

                // return [
                //     'title'    => $item->name,
                //     'url'      => $pageUrl,
                //     'mtime'    => $item->updated_at,
                //     'isActive' => $pageUrl === $url,
                //     'viewBag'  => [
                //         'cover' => $item->cover
                //     ],
                // ];
            })
            ->toArray();
        return [
            'items' => $items,
        ];
    }

}
