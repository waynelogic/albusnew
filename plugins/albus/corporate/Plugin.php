<?php namespace Albus\Corporate;

/** October CMS */
use System\Classes\PluginBase;

/** Albus\Corporate */
use Albus\Corporate\Classes\Registration\Boot;
use Albus\Corporate\Classes\Registration\RegisterNavigation;
use Albus\Corporate\Classes\Registration\RegisterComponents;
use Albus\Corporate\Classes\Registration\RegisterPermissions;
use Albus\Corporate\Classes\Registration\RegisterMarkupTags;
/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'Corporate',
            'description' => 'Универсальный плагин для создания корпоративного сайта',
            'author' => 'Albus',
            'icon' => 'icon-suitcase'
        ];
    }

    use Boot;
    use RegisterComponents;
    use RegisterPermissions;
    use RegisterNavigation;
    use RegisterMarkupTags;
}
