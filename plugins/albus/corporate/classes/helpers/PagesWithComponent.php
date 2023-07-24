<?php namespace Albus\Corporate\Classes\Helpers;

use Cms\Classes\Page as CmsPage;
use Cms\Classes\Theme;

trait PagesWithComponent {
    public function pagesWithComponent($component) {
        $theme = Theme::getActiveTheme();
        $pages = CmsPage::listInTheme($theme, true);
        $cmsPages = [];
        foreach ($pages as $page) {
            if ($page->hasComponent($component)) {
                $cmsPages[$page->baseFileName] = $page->title;
            }
        }
        return $cmsPages;
    }
}