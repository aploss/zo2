<?php

/**
 * Zo2 - A powerful Joomla template framework
 * @version     1.5.3
 * @link        http://www.zootemplate.com/zo2
 * @author      ZooTemplate <http://www.zootemplate.com>
 * @copyright   CleverSoft (http://cleversoft.co/)
 * @license     GPL v2
 */

defined('_JEXEC') or die();


class PkgZo2InstallerScript
{
    /**
     * Called before any type of action
     *
     * @param     string              $route      Which action is happening (install|uninstall|discover_install)
     * @param     jadapterinstance    $adapter    The object responsible for running this script
     *
     * @return    boolean                         True on success
     */
    public function preFlight($route, JAdapterInstance $adapter)
    {
        return true;
    }


    /**
     * Called after any type of action
     *
     * @param     string              $route      Which action is happening (install|uninstall|discover_install)
     * @param     jadapterinstance    $adapter    The object responsible for running this script
     *
     * @return    boolean                         True on success
     */
    public function postFlight($route, JAdapterInstance $adapter)
    {
        return true;
    }
}
