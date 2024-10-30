<?php

namespace Kinetise\Controller;

use KinetiseApi\Controller\AbstractController;
use KinetiseApi\HTTP\KinetiseDataFeedResponse;
use KinetiseApi\HTTP\KinetiseResponse;
use KinetiseApi\UrlGenerator;

class IndexController extends AbstractController
{
    /**
     * @return KinetiseDataFeedResponse
     *
     * @Kinetise\MethodDesc Default endpoint
     * @Kinetise\RegestUri ?kinetise|?kinetise=index:index
     */
    public function indexAction()
    {
        $siteIcon = \get_site_icon_url();
        $data = array(
            array(
                'title' => 'Pages',
                'url' => UrlGenerator::generate('pages'),
                '_coverImage' => $siteIcon,
                '__pluginVersion' => KINETISE_PLUGIN_VERSION
            ),
            array(
                'title' => 'Categories',
                'url' => UrlGenerator::generate('categories'),
                '_coverImage' => $siteIcon,
                '__pluginVersion' => KINETISE_PLUGIN_VERSION
            ),
            array(
                'title' => 'Posts',
                'url' => UrlGenerator::generate('posts'),
                '_coverImage' => $siteIcon,
                '__pluginVersion' => KINETISE_PLUGIN_VERSION
            )
        );

        return new KinetiseDataFeedResponse($data);
    }
}
