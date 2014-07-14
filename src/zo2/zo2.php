<?php

/**
 * Zo2 (http://www.zo2framework.org)
 * A powerful Joomla template framework
 *
 * @link        http://www.zo2framework.org
 * @link        http://github.com/aploss/zo2
 * @author      ZooTemplate <http://zootemplate.com99
 * @copyright   Copyright (c) 2013 APL Solutions (http://apl.vn)
 * @license     GPL v2
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Class exists checking
 */
if (!class_exists('plgSystemZo2')) {

    /**
     * Zo2 Framework entrypoint plugin
     */
    class plgSystemZo2 extends JPlugin {

        /**
         * 
         * @param type $subject
         * @param type $config
         */
        public function __construct(& $subject, $config) {
            parent::__construct($subject, $config);
            $language = JFactory::getLanguage();
            $language->load('plg_system_zo2', JPATH_ADMINISTRATOR);
        }

        /**
         * Init our framework
         */
        public function onAfterInitialise() {
            include_once __DIR__ . '/includes/bootstrap.php';
        }
  
        /**
         * 
         */
        public function onAfterRender() {
            if (Zo2Factory::isZo2Template()) {
                $app = JFactory::getApplication();
                $body = JResponse::getBody();
                $shortcodes = Zo2Shortcodes::getInstance();
                $jinput = JFactory::getApplication()->input;
                $framework = Zo2Factory::getFramework();

                if ($app->isAdmin()) {
                    
                } else {
                    /* Make sure shortcodes enabled and we are not in any "edit" tasking ! */
                    if ($framework->get('enable_shortcodes', 1) == 1 && ( $jinput->get('task') != 'edit' )) {
                        /* Do shortcodes process */
                        $body = $shortcodes->execute($body);
                    }
                }

                $assets = Zo2Assets::getInstance();
                $body = str_replace('</body>', $assets->generateAssets('js') . '</body>', $body);
                $body = str_replace('</head>', $assets->generateAssets('css') . '</head>', $body);

                /* Apply back to body */
                JResponse::setBody($body);
            }
        }

        public function onContentPrepare($context, &$article, &$params, $page = 0) {
            if (Zo2Factory::isZo2Template()) {
                $framework = Zo2Factory::getFramework();
                $config = Zo2Factory::getTemplate()->params;
                // Don't run this plugin when the content is being indexed
                if ($context == 'com_finder.indexer') {
                    return true;
                }

                if (JFactory::getApplication()->isSite()) {
                    //$article->text = $this->doShortCode($article->text);
                    /* Google Authorship */
                    if ((int) $config->get("enable_googleauthorship", 0)) {
                        $author_name = "+";
                        $user = JFactory::getUser();
                        $rel = 'me';
                        if ($user->get('id') == $article->created_by) {
                            $rel = 'author';
                        }

                        $gplus = '<a href="' . $config->get('google_profile_url', '') . '/?rel=' . $rel . '"';
                        $gplus .= ' title="Google Plus Profile for ' . $author_name . '" plugin="Google Plus Authorship">' . $author_name . '</a>';
                        $article->text = $gplus . $article->text;
                    }
                    /* Comments System */
                    if ($config->get('enable_comments', 0) && !$framework->isFrontPage()) {
                        if (JFactory::getApplication()->input->getCmd('option') != 'com_k2') {
                            $view = JFactory::getApplication()->input->get('view');
                            if ($view == 'article') {
                                Zo2Factory::import('addons.comments.Zo2Comments');
                                $comment = new Zo2Comments($article);
                                $article->text = $article->text . $comment->renderHtml();
                            }
                        }
                    }
                }
            }
        }

    }

}

