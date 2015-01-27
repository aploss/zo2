<?php

/**
 * Zo2 (http://www.zootemplate.com/zo2)
 * A powerful Joomla template framework
 *
 * @link        http://www.zootemplate.com/zo2
 * @link        https://github.com/cleversoft/zo2
 * @author      ZooTemplate <http://zootemplate.com>
 * @copyright   Copyright (c) 2014 CleverSoft (http://cleversoft.co/)
 * @license     GPL v2
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Class exists checking
 */
if (!class_exists('Zo2LayoutbuilderAdmin'))
{

    /**
     * Layout builder for administrator
     */
    class Zo2LayoutbuilderAdmin extends Zo2LayoutbuilderAbstract
    {

        /**
         * Render layout builder for backend from json
         * @return string
         */
        public function render()
        {
            $html = new Zo2Html();
            $html->set('layout', $this->getProperties());
            return $html->fetch('Zo2://html/admin/layout/layoutbuilder.php');
        }

    }

}