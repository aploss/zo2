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

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

/**
 * Class exists checking
 */
if (!class_exists('Zo2Factory')) {

    /**
     * Zo2 Framework factory class
     */
    class Zo2Factory {

        /**
         * Get singleton instance of Zo2 Framework
         * @return Zo2Framework
         */
        public static function getFramework($template = null) {
            static $instances = array();
            if ($template === null) {
                $template = self::getTemplate();
            }
            if ($template) {
                if (!isset($instances[$template->id])) {
                    $instances[$template->id] = Zo2Framework::getInstance($template);
                }
                return $instances[$template->id];
            }
            return false;
        }

        /**
         *
         * @staticvar array $instances
         * @param int $id
         * @return object
         */
        public static function getTemplate($id = null) {
            static $instances;
            /* Get specific template id */
            if ($id !== null) {
                if (isset($instances[$id])) {
                    return $instances[$id];
                }
                $db = JFactory::getDBO();
                $query = ' SELECT * FROM ' . $db->quoteName('#__template_styles') .
                        ' WHERE ' . $db->quoteName('id') . ' = ' . (int) $id;
                $db->setQuery($query);
                $template = $db->loadObject();
                if ($template) {
                    $template->params = new JRegistry($template->params);
                }
                $instances[$id] = $template;
                return $instances[$id];
            } else {
                /* Get current template */
                if (JFactory::getApplication()->isSite()) {
                    /**
                     * Somehow we can't use getActiveMenu here
                     * @todo Need to improve process
                     */
                    $itemId = JFactory::getApplication()->input->get('Itemid');
                    $db = JFactory::getDbo();
                    $query = ' SELECT ' . $db->quoteName('template_style_id');
                    $query .= ' FROM ' . $db->quoteName('#__menu');
                    $query .= ' WHERE ' . $db->quoteName('id') . ' = ' . (int) $itemId;
                    $db->setQuery($query);
                    $templateSiteId = $db->loadResult();
                    if ($templateSiteId && $templateSiteId != 0) {
                        /* Get request template record */
                        $id = $templateSiteId;
                        $template = self::getTemplate($templateSiteId);
                    } else {
                        $template = JFactory::getApplication()->getTemplate(true);
                        $id = $template->id;
                    }
                    $instances[$id] = $template;
                    return $instances[$id];
                } else {
					$option = JFactory::getApplication()->input->get('option');
                    /* Get requesting template for backend only */
                    $id = JFactory::getApplication()->input->get('id');
                    if ($id && $option == 'com_templates') {
                        return self::getTemplate($id);
                    }
                }
            }
        }

        /**
         * Get template name
         * @return string
         */
        public static function getTemplateName() {
            $template = self::getTemplate();
            if ($template)
                return $template->template;
        }

        /**
         * Get current template params
         * @param type $name
         * @param type $default
         * @return type
         */
        public static function get($name, $default = null) {
            return self::getFramework()->get($name, $default);
        }

        /**
         * Get current template params
         * @param type $name
         * @param type $default
         * @return type
         */
        public static function set($name, $value) {
            return self::getTemplate()->params->$value($name, $value);
        }

        /**
         * 
         * @param type $namespace
         * @param type $path
         */
        public static function registerNamespace($namespace, $path) {
            return Zo2Path::getInstance()->registerNamespace($namespace, $path);
        }

        /**
         * Retun physical path by input key
         * @param type $name
         * @return type
         */
        public static function getPath($key) {
            return Zo2Path::getInstance()->getPath($key);
        }

        /**
         * Return url by input key
         * @param type $name
         * @return type
         */
        public static function getUrl($key) {
            return Zo2Path::getInstance()->getUrl($key);
        }

        /**
         * Is frontend
         * @return type
         */
        public static function isSite() {
            return JFactory::getApplication()->isSite();
        }

        /**
         *
         * @return boolean
         */
        public static function isJoomla25() {
            $jVer = new JVersion();
            return $jVer->RELEASE == '2.5';
        }

        /**
         * 
         * @return boolean
         */
        public static function isZo2Template() {
            $template = self::getTemplate();
            if ($template) {
                $templateConfig = JPATH_ROOT . '/templates/' . $template->template . '/assets/template.json';
                return JFile::exists($templateConfig);
            }
            return false;
        }

        /**
         * Execute an action of the controller
         */
        public static function execController() {
            if ($zo2controller = JFactory::getApplication()->input->getCmd('zo2controller')) {
                Zo2Controller::exec($zo2controller);
            }
        }

        /**
         * Return current page.
         *
         * @return string
         */
        public static function getCurrentPage() {
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            if (isset($menu)) {
                $activeMenu = $menu->getActive();
                if (isset($activeMenu) && $activeMenu->home)
                    return 'homepage';
            }

            return $app->input->getString('view', 'homepage');
        }

        /**
         * Import file from Zo2Framework plugin directory
         *
         * @param string $filepath Dot syntax file path
         * @param bool $once Require this file only once
         * @return bool
         */
        public static function import($filePath, $once = true) {
            $path = ZO2PATH_ROOT . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $filePath) . '.php';
            if (JFile::exists($path)) {
                return $once ? include_once $path : include $path;
            }
            return false;
        }

        /**
         * Get instance of Zo2Profile by name
         * @staticvar array $profiles
         * @param type $profile
         * @return \Zo2Profile
         */
        public static function getProfile($profile = null) {
            static $profiles = array();
            $profileName = 'default';
            if ($profile === null) {
                $requestProfile = JFactory::getApplication()->input->get('profile');
                /* Get requested profile via url parameter */
                if ($requestProfile) {
                    $profileName = $requestProfile;
                } else {
                    /* Get request profile base on assigned menu */
                    $itemId = JFactory::getApplication()->input->get('Itemid');
                    /* Get profiles list */
                    $list = self::getFramework()->get('profile', 'default');
                    if (is_object($list)) {
                        if (isset($list->$itemId)) {
                            $profileName = $list->$itemId;
                        } else {
                            $profileName = 'default';
                        }
                    } else {
                        if (is_array($profiles)) {
                            $profileName = 'default';
                        }
                    }
                }
            } else {
                if (is_string($profile)) {
                    $profileName = $profile;
                } elseif ($profile instanceof Zo2Profile) {
                    $profileName = $profile->name;
                }
            }
            if (!isset($profiles[$profileName])) {
                $profile = new Zo2Profile();
                $profile->load($profileName);
                $profiles[$profileName] = $profile;
            }
            return $profiles[$profileName];
        }

    }

}