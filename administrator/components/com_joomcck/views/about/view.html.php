<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

use Joomla\CMS\Installer\Installer;

defined('_JEXEC') || die();
jimport('joomla.application.component.view');
/**
 * View information about joomcck.
 *
 * @package        Joomcck
 * @subpackage    com_joomcck
 * @since        6.0
 */
class JoomcckViewAbout extends MViewBase
{

    public function display($tpl = null)
    {
        $this->addToolbar();

        $data2 = ['version' => 'Not Installed'];

        $file = JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'joomcck.xml';
        $data = Installer::parseXMLInstallFile($file);

        $fields_path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_joomcck' . DIRECTORY_SEPARATOR . 'fields';

        $db = \Joomla\CMS\Factory::getDbo();
        $db->setQuery("SELECT element, manifest_cache FROM `#__extensions` WHERE `name` LIKE 'Joomcck - Field - %'");
        $fields = $db->loadObjectList();

        foreach ($fields as $key => $field) {
            $fields[$key]->name    = ucfirst($field->element);
            $mnf                   = new \Joomla\Registry\Registry($field->manifest_cache);
            $fields[$key]->version = $mnf->get('version');

        }

        $this->data   = $data;
        $this->fields = $fields;

        \Joomla\CMS\Table\Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');
        $menu_table = \Joomla\CMS\Table\Table::getInstance('Menu', 'JTable', []);

        $menu_table->load([
            "link" => 'index.php?option=com_joomcck&view=cpanel',
            "type" => 'component'
        ]);

        $live_site    = substr(\Joomla\CMS\Uri\Uri::root(), 0, -1);
        $app          = Joomla\CMS\Application\CMSApplication::getInstance('site');
        $router       = $app->getRouter();
        $url          = $router->build($live_site . '/index.php?option=com_joomcck&view=cpanel&Itemid=' . $menu_table->id);
        $this->linkCP = $url->toString();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(\Joomla\CMS\Language\Text::_('About'));
        MRToolBar::addSubmenu('about');
    }
}
