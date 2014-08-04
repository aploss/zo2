<?php

/**
 * Zo2 (http://www.zo2framework.org)
 * A powerful Joomla template framework
 *
 * @link        http://www.zo2framework.org
 * @link        http://github.com/aploss/zo2
 * @author      ZooTemplate <http://zootemplate.com>
 * @copyright   Copyright (c) 2013 APL Solutions (http://apl.vn)
 * @license     GPL v2
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Class Zo2Component_header_logo
 *
 * This class will prepend the logo, sitename, slogan to the header_logo position
 */
class Zo2Component_header_logo extends Zo2Component {

    public $position = Zo2Component::RENDER_BEFORE;

    public function render() {
        $framework = Zo2Factory::getFramework();

        $logo = $framework->get('header_logo');
        $logoRetina = $framework->get('header_retina_logo');
        $sitename = $framework->get('site_name');
        $slogan = $framework->get('site_slogan');

        if (!empty($logo) && !empty($logoRetina)) {
            $logo = json_decode($logo, true);
            $logoRetina = json_decode($logoRetina, true);
            $customStyle = '.logo_retina { display: none; }
                .logo_normal {display:block}
                @media screen and (min-resolution: 2dppx), @media screen and (-webkit-min-device-pixel-ratio: 2) {
                    .logo_normal { display: none; }
                    .logo_retina { display: block; }
                }@media screen and (-webkit-min-device-pixel-ratio: 2){
                    .logo_normal { display: none; }
                    .logo_retina { display: block; }
                }';

            // still having bug with this
            //$framework->getLayout()->insertCssDeclaration($customStyle);
            $framework->assets->addStyleSheetDeclaration($customStyle);

            $html = '<header id="header_logo">';
            if ($logo['type'] == 'text') {
                $html .= htmlspecialchars($logo['text']);
            } else {
                if ($logo['type'] == 'image' && !empty($logo['path'])) {
                    $style = '';
                    if($logo['width'] > 0) {
                        $style .= 'width: ' . $logo['width'] . 'px;';
                    }
                    if($logo['height'] > 0) {
                        $style .= 'height: ' . $logo['height'] . 'px;';
                    }
                    $html .= '<a class="logo_normal" href="' . JUri::root(true) . '" title="' . (!empty($sitename) ? $sitename : '') . '"><img alt="Logo" src="'.JUri::root(true) . '/' . $logo['path'].'" style="'.$style.'" /></a>';
                }
                if ($logoRetina['type'] == 'image' && !empty($logoRetina['path'])) {
                    $style = '';
                    if($logoRetina['width'] > 0) {
                        $style .= 'width: ' . $logoRetina['width'] . 'px;';
                    }
                    if($logoRetina['height'] > 0) {
                        $style .= 'height: ' . $logoRetina['height'] . 'px;';
                    }
                    $html .= '<a class="logo_retina" href="' . JUri::root(true) . '" title="' . (!empty($sitename) ? $sitename : '') . '"><img alt="Logo" src="'.JUri::root(true) . '/' . $logoRetina['path'].'" style="style="'.$style.'" /></a>';
                }
            }
            $html .=!empty($slogan) ? '<h2 class="header_slogan">' . $slogan . '</h2>' : '';
            $html .= '</header>';

            return $html;
        } else if (!empty($logo) && empty($logoRetina)) {
            $html = '<header id="header_logo"><h1><a href="/" title="' . (!empty($sitename) ? $sitename : '') . '">';
            $html .= '<img class="logo_normal" src="' . $logo . '" />';
            $html .= '</a></h1>';
            $html .=!empty($slogan) ? '<h2>' . $slogan . '</h2>' : '';
            $html .= '</header>';

            return $html;
        } else
            return '';
    }

}
