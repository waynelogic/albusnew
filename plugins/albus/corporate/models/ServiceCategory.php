<?php namespace Albus\Corporate\Models;

use Model;
use System\Models\File;
use Cms\Classes\Theme;
use Cms\Classes\Page as CmsPage;
use Cms\Classes\Controller;
use Albus\Corporate\Models\Service;

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

    public $hasMany = [
        'services' => [ Service::class, 'key' => 'category_id']
    ];    


    public function getPageUrl($pageId) {
        $controller = Controller::getController();
        return $controller->pageUrl($pageId, [
            'category' => $this->slug
        ]);
    }

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
            ::whereActive(true)->get()
            ->map(function (self $item) use ($cmsPage, $url, $pageName) {
                return [
                    'title'    => $item->name,
                    'url'      => $item->getPageUrl($cmsPage->id),
                    'mtime'    => $item->updated_at,
                    'isActive' => $item->getPageUrl($cmsPage->id) === $url,
                    'viewBag'  => [
                        'cover' => $item->cover
                    ],
                ];
            })
            ->toArray();
        return [
            'items' => $items,
        ];
    }

}
