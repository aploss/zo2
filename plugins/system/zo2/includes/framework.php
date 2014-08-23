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
if (!class_exists('Zo2Framework')) {

    /**
     * Zo2 Framework object class
     */
    class Zo2Framework {

        /**
         * @var Zo2Framework
         */
        protected static $_instances;

        /**
         * Joomla! template object
         * @var object
         */
        public $template = null;

        /**
         *
         * @var Zo2Assets
         */
        public $assets = null;

        /**
         *
         * @var Zo2Profile
         */
        public $profile = null;
        public $layout = null;
        protected $_addons = array();

        /**
         * Constructor
         * @param type $template
         */
        protected function __construct($template) {
            $this->template = $template;
        }

        /**
         * Get instance of Zo2 Framework with specific template
         * @param object|null $template
         * @return Zo2Framework
         */
        public static function getInstance($template = null) {
            if ($template === null)
                $template = Zo2Factory::getTemplate();
            if (!self::$_instances[$template->template]) {
                self::$_instances[$template->template] = new Zo2Framework($template);
            }
            return self::$_instances[$template->template];
        }

        /**
         * Framework init
         */
        public function init() {
			if (!defined('ZO2_LOADED')) {			
				$jinput = JFactory::getApplication()->input;
				/* Init framework variables */
				$this->assets = Zo2Assets::getInstance();
				$this->profile = Zo2Factory::getProfile($jinput->getWord('profile'));
				$this->layout = new Zo2Layout($this->profile->layout);

				/* Get specific core assets */
				if (Zo2Factory::isJoomla25()) {
					$assetsFile = 'assets.joomla25.json';
				} else {
					$assetsFile = 'assets.default.json';
				}
				/* Load Zo2' assets */
				$assetsFile = Zo2Factory::getPath('zo2://assets/' . $assetsFile);
				if ($assetsFile) {
					$assets = json_decode(file_get_contents($assetsFile));
					/* Site loading */
					if (Zo2Factory::isSite()) {
						/* Load core assets */
						$this->assets->load($assets->frontend);
						/* Responsive */
						if ($this->get('responsive_layout') == 1)
							$this->assets->addStyleSheet('zo2/css/non-responsive.css');
						/* Custom css */
						if ($this->get('enable_custom_css', 1) == 1)
							$this->assets->addStyleSheet('zo2/css/custom.css');
						/* Template side */
						$templateAssets = $this->getAssets();
						if ($templateAssets && isset($templateAssets->assets)) {
							$this->assets->load($templateAssets->assets);
						}
						/* Load bootstrap-rtl if needed */
						if (JFactory::getLanguage()->isRTL() && $this->get('enable_rtl') == 1) {
							$this->assets->addStyleSheet('vendor/bootstrap/addons/bootstrap-rtl/css/bootstrap-rtl.min.css');
						}
						$this->_loadProfile();
					} else {
						/* Backend loading */
						if (Zo2Factory::isZo2Template()) {
							/* Load core assets */
							$this->assets->load($assets->backend);
						}
					}
				} else {
					JFactory::getApplication()->enqueueMessage('Zo2 assets file not found');
				}
				define('ZO2_LOADED',1);
			}            
        }

        /**
         * Get template params' property
         * @param string $property
         * @param mixed $default
         *
         * @return mixed
         */
        public function get($property, $default = null) {
            if ($this->template->params instanceof JRegistry)
                return $this->template->params->get($property, $default);
            return $default;
        }

        public function getPath($key) {
            return Zo2Factory::getPath('templates://' . $key);
        }

        public function getUrl($key) {
            return Zo2Factory::getUrl('templates://' . $key);
        }

        /**
         * Get template assets object
         * @return boolean
         */
        public function getAssets() {
            $assetsFile = $this->getPath('assets/template.json');
            if ($assetsFile) {
                return json_decode(file_get_contents($assetsFile));
            }
        }

        /**
         *
         * @return array
         */
        public function getTemplatePositions() {
            $path = $this->getPath('templateDetails.xml');
            $positions = array();
            if ($path) {
                $xml = simplexml_load_file($path);
                $positions = (array) $xml->positions;
                if (isset($positions['position']))
                    $positions = $positions['position'];
                else
                    $positions = array();
            }
            return $positions;
        }

        /**
         * Generate custom CSS style for Standard Font option
         *
         * @param $data
         * @param $selector
         */
        protected function _buildStandardFontStyle($data, $selector) {
            $assets = Zo2Assets::getInstance();
            $style = '';
            if (!empty($data['family']))
                $style .= 'font-family:' . $data['family'] . ';';
            if (!empty($data['size']) && $data['size'] > 0)
                $style .= 'font-size:' . $data['size'] . 'px;';
            if (!empty($data['color']))
                $style .= 'color:' . $data['color'] . ';';
            if (!empty($data['style'])) {
                switch ($data['style']) {
                    case 'b': $style .= 'font-weight:bold;';
                        break;
                    case 'i': $style .= 'font-style:italic;';
                        break;
                    case 'bi':
                    case 'ib':
                        $style .= 'font-weight:bold;font-style:italic;';
                        break;
                    default:
                        break;
                }
            }

            if (!empty($style)) {
                $style = $selector . '{' . $style . '}' . "\n";

                $assets->addStyleSheetDeclaration($style);
            }
        }

        /**
         * @param $data
         * @param $selector
         */
        protected function _buildGoogleFontsStyle($data, $selector) {
            $assets = Zo2Assets::getInstance();
            $api = 'http://fonts.googleapis.com/css?family=';
            $url = '';
            $style = '';
            if (!empty($data['family'])) {
                $style .= 'font-family:' . $data['family'] . ';';
                $url = $api . urlencode($data['family']);
            }
            if (!empty($data['size']) && $data['size'] > 0)
                $style .= 'font-size:' . $data['size'] . 'px;';
            if (!empty($data['color']))
                $style .= 'color:' . $data['color'] . ';';
            if (!empty($data['style'])) {
                switch ($data['style']) {
                    case 'b':
                        $style .= 'font-weight:bold;';
                        $url .= ':700';
                        break;
                    case 'i':
                        $style .= 'font-style:italic;';
                        $url .= ':400italic';
                        break;
                    case 'bi':
                    case 'ib':
                        $style .= 'font-weight:bold;font-style:italic;';
                        $url .= ':700italic';
                        break;
                    default:
                        break;
                }
            }

            if (!empty($style)) {
                $doc = JFactory::getDocument();
                $doc->addStyleSheet($url);
                //$this->addStyleSheet($url);
                $style = $selector . '{' . $style . '}' . "\n";
                $assets->addStyleSheetDeclaration($style);
            }
        }

        /**
         * Generate custom CSS style for FontDeck option
         *
         * @param $data
         * @param $selector
         */
        protected function _buildFontDeckStyle($data, $selector) {
            $fontdeckCode = $this->get('fontdeck_code');
            $assets = Zo2Assets::getInstance();

            if (!empty($fontdeckCode)) {
                $assets->addScriptDeclaration($fontdeckCode);
            }

            $style = '';
            if (!empty($data['family']))
                $style .= 'font-family:' . $data['family'] . ';';
            if (!empty($data['size']) && $data['size'] > 0)
                $style .= 'font-size:' . $data['size'] . 'px;';
            if (!empty($data['color']))
                $style .= 'color:' . $data['color'] . ';';
            if (!empty($data['style'])) {
                switch ($data['style']) {
                    case 'b': $style .= 'font-weight:bold;';
                        break;
                    case 'i': $style .= 'font-style:italic;';
                        break;
                    case 'bi':
                    case 'ib':
                        $style .= 'font-weight:bold;font-style:italic;';
                        break;
                    default:
                        break;
                }
            }

            if (!empty($style)) {
                $style = $selector . '{' . $style . '}' . "\n";
                $assets->addStyleSheetDeclaration($style);
            }
        }

        /**
         *
         */
        protected function _loadProfile() {
            $style = '';
            $zPath = Zo2Path::getInstance();
            if ($this->profile->theme) {
                $presetData = get_object_vars($this->profile->theme);

                if (!empty($presetData['background']))
                    $style .= 'body{background-color:' . $presetData['background'] . '}';
                if (!empty($presetData['header']))
                    $style .= '#zo2-header{background-color:' . $presetData['header'] . '}';
                if (!empty($presetData['header_top']))
                    $style .= '#zo2-header-top{background-color:' . $presetData['header_top'] . '}';
                if (!empty($presetData['text']))
                    $style .= 'body{color:' . $presetData['text'] . '}';
                if (!empty($presetData['link']))
                    $style .= 'a{color:' . $presetData['link'] . '}';
                if (!empty($presetData['link_hover']))
                    $style .= 'a:hover{color:' . $presetData['link_hover'] . '}';
                if (!empty($presetData['bottom1']))
                    $style .= '#zo2-bottom1{background-color:' . $presetData['bottom1'] . '}';
                if (!empty($presetData['bottom2']))
                    $style .= '#zo2-bottom2{background-color:' . $presetData['bottom2'] . '}';
                if (!empty($presetData['footer']))
                    $style .= '#zo2-footer{background-color:' . $presetData['footer'] . '}';
                if (!empty($presetData['extra'])) {
                    $extra = json_decode($presetData['extra']);
                    if (count($extra) > 0) {
                        foreach ($extra as $element => $value) {
                            if (!empty($element))
                                $style .= $element . '{background-color:' . $value . '}';
                        }
                    }
                }

                if (!empty($presetData['bg_image'])) {
                    $style .= 'body.boxed {background-image: url("' . JUri::root() . $presetData['bg_image'] . '")}';
                } elseif (!empty($presetData['bg_pattern'])) {
                    $style .= 'body.boxed {background-image: url("' . $zPath->toUrl($presetData['bg_pattern']) . '")}';
                }

                if (!empty($presetData['css']))
                    $this->assets->addStyleSheet('zo2/css/' . $presetData['css'] . '.css');
            }

            $this->assets->addStyleSheetDeclaration($style);

            /* Prepare Fonts */
            $selectors = array('body_font' => 'body', 'h1_font' => 'h1',
                'h2_font' => 'h2', 'h3_font' => 'h3', 'h4_font' => 'h4',
                'h5_font' => 'h5', 'h6_font' => 'h6'
            );

            foreach ($selectors as $param => $selector) {
                $value = $this->get($param);

                if (!empty($value)) {
                    $data = json_decode($value, true);
                    if (isset($data['type']) && !empty($data['type'])) {
                        switch ($data['type']) {
                            case 'standard':
                                $this->_buildStandardFontStyle($data, $selector);
                                break;
                            case 'googlefonts':
                                $this->_buildGoogleFontsStyle($data, $selector);
                                break;
                            case 'fontdeck':
                                $this->_buildFontDeckStyle($data, $selector);
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }

        public function isBoxed() {
            if ($this->profile->theme && $this->profile->theme->boxed == 1)
                return true;
            return false;
        }

        public function compileLess($lessPath, $templateName = '') {
            $filename = md5($lessPath) . '.css';
            $absPath = JPATH_SITE . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName .
                    DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $filename;
            $relPath = 'assets/cache/' . $filename;
            if (!file_exists($absPath)) {
                if (!class_exists('lessc', false))
                    Zo2Factory::import('vendor.less.lessc');
                $absLessPath = JPATH_SITE . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $templateName . $lessPath;

                $compiler = new lessc();
                $style = $compiler->compileFile($absLessPath);

                Zo2HelperAssets::forcePutContent($absPath, $style);
            }
            return $relPath;
        }

        /**
         * Get list of data components of current template. Usable from backend only.
         *
         * @param string $templateName
         * @return string
         */
        public function getComponents($templateName) {
            if (!empty($templateName)) {
                $path = JPATH_SITE . '/templates/' . $templateName . '/data/components.json';
                if (file_exists($path)) {
                    $content = file_get_contents($path);
                    echo $content;
                }
            }

            return '';
        }

        /**
         * Return singleton Zo2Layout instance
         * @staticvar Zo2Layout $instances
         * @return \Zo2Layout
         */
        public function getLayout($templateName = null) {
            static $instances;
            $templateId = Zo2Factory::getTemplate()->id;
            if (!isset($instances[$templateId])) {
                $instances[$templateId] = new Zo2Layout();
            }
            return $instances[$templateId];
        }

        /**
         * Get list of layouts from this template
         *
         * @param int $templateId If pass null, or 0, templateId will get from $_GET['id']
         * @return array
         */
        public function getTemplateLayouts($templateId = 0) {
            $templateName = Zo2Factory::getTemplateName($templateId);

            if (!empty($templateName)) {
                $templatePath = JPATH_SITE . '/templates/' . $templateName . '/layouts/*.php';
                $layoutFiles = glob($templatePath);
                return array_map('basename', $layoutFiles, array('.php'));
            } else
                return array();
        }

        public function getTemplateLayoutsName($templateName) {
            if (!empty($templateName)) {
                $templatePath = JPATH_SITE . '/templates/' . $templateName . '/layouts/*.json';
                $layoutFiles = glob($templatePath);

                $result = array();

                for ($i = 0, $total = count($layoutFiles); $i < $total; $i++) {
                    $layoutFiles[$i] = basename($layoutFiles[$i]);
                    if ($layoutFiles[$i] !== 'core.json' && $layoutFiles[$i] !== 'megamenu.json') {
                        $result[] = str_replace('.json', '', $layoutFiles[$i]);
                    }
                }

                return json_encode($result);
            } else
                return json_encode(array());
        }

        /**
         * Return current page.
         *
         * @return string
         */
        public function getCurrentPage() {
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
         *
         * @param string $menutype
         * @return string
         */
        public function getMenuType($menuType = null) {
            if ($menuType === null) {
                $menuType = $this->get('menu_type', 'mainmenu');
            }
            return $menuType;
        }

        /**
         *
         * @param string $menutype
         * @return array
         */
        public function getMenuItems($menuType = null) {
            $menuType = $this->getMenuType($menuType);
            $menu = JFactory::getApplication()->getMenu();
            return $menu->getItems('menutype', $menuType);
        }

        /**
         * Get HTML for Megamenu
         * @param string $menutype
         * @param string $isAdmin True to generate megamenu in backend
         * @return string
         */
        public function displayMegaMenu($menutype = null, $isAdmin = false) {
            if ($menutype === null) {
                $menutype = self::get('menu_type', 'mainmenu');
            }
            $menu = new Zo2MegaMenu($menutype);
            return $menu->renderMenu($isAdmin);
        }

        /**
         * @todo Create new renderMenu to wrap both of mega & canvas menu function
         * @todo Parameter is object / array config
         * @param type $config
         * @param type $isAdmin
         * @return type
         */
        public function displayOffCanvasMenu($config = null, $isAdmin = false) {
            if (!isset($config['menu_type'])) {
                $config['menu_type'] = self::get('menu_type', 'mainmenu');
            }
            if (!isset($config['isAdmin'])) {
                $config['isAdmin'] = false;
            }
            $menu = new Zo2MegaMenu($config['menu_type'], $config);
            return $menu->renderOffCanvasMenu($config);
        }

        /**
         * @return bool
         */
        public function isFrontPage() {

            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $tag = JFactory::getLanguage()->getTag();

            if ($menu->getActive() == $menu->getDefault($tag)) {
                return true;
            } else {
                return false;
            }
        }

        public function getAsset($name, $data = array()) {
            static $assets = array();
            if (empty($assets[$name])) {
                $assets[$name] = new Zo2Asset($data);
            }
            return $assets[$name];
        }

        public function getManifest() {
            $xml = JFactory::getXML(ZO2PATH_ROOT . '/zo2.xml');
            return $xml;
        }

        public function getTemplateManifest() {
            $xml = JFactory::getXML(Zo2Factory::getPath('templates://templateDetails.xml'));
            return $xml;
        }

        /**
         * Check Zo2 version is up to date
         * @return int
         */
        public function checkVersion() {
            $remoteXML = simplexml_load_file('http://update.zo2framework.org/zo2/extension.xml');
            $result['compare'] = 0;
            $result['currentVersion'] = (string) $this->getManifest()->version;
            $result['latestVersion'] = 'Unknown';
            if ($remoteXML) {
                $result['latestVersion'] = (string) $remoteXML->update[0]->version;
                $result['compare'] = version_compare($result['currentVersion'], $result['latestVersion']);
            }
            return $result;
        }

        /**
         * Get array of profiles
         * @return \Zo2Profile
         */
        public function getProfiles() {
            $templateDir = Zo2Factory::getPath('templates://assets/profiles/' . $this->template->id);
            $profiles = array();
            if (JFolder::exists($templateDir)) {
                $files = JFolder::files($templateDir);
                if ($files) {
                    foreach ($files as $file) {
                        if (JFile::getExt($file) == 'json') {
                            $profile = new Zo2Profile();
                            $profile->load(JFile::stripExt($file));
                            $profiles[JFile::stripExt($file)] = $profile;
                        }
                    }
                }
            }
            if (empty($profiles))
                $profiles[] = 'default';
            return $profiles;
        }

        public function joomla($name) {
            $filePath = ZO2PATH_ROOT . '/libraries/joomla/' . $name . '.php';
            if (JFile::exists($filePath)) {
                require_once $filePath;
            }
            $className = 'Zo2J' . ucfirst($name);
            if (class_exists($className)) {
                return new $className();
            }
        }

        public function __get($name) {
            $properties = get_object_vars($this);
            if (isset($properties[$name])) {
                return $properties;
            } else {
                
            }
        }

        /**
         * Register addons that will render
         * @param type $name
         * @param type $callback
         * @return \Zo2Framework
         */
        public function registerAddon($name, $callback) {
            $this->_addons[$name] = $callback;
            return $this;
        }

        public function getRegisteredAddons() {
            return $this->_addons;
        }

    }

}