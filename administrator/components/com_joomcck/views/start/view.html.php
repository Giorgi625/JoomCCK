<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

use Joomla\CMS\Factory;
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
class JoomcckViewStart extends MViewBase
{

    public function display($tpl = null)
    {
		$this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolBarHelper::title(JText::_('Start'));
    }

	public function checkAdminDashboardMenuItem(){

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');
		$menu_table = JTable::getInstance('Menu', 'JTable', []);

		$result = $menu_table->load([
			"link" => 'index.php?option=com_joomcck&view=cpanel',
			"type" => 'component'
		]);


		return $result;


	}


	public function getAdminDashboardLink(){

		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');
		$menu_table = JTable::getInstance('Menu', 'JTable', []);

		$menu_table->load([
			"link" => 'index.php?option=com_joomcck&view=cpanel',
			"type" => 'component'
		]);

		$live_site    = substr(JURI::root(), 0, -1);
		$app          = Joomla\CMS\Application\CMSApplication::getInstance('site');
		$router       = $app->getRouter();
		$url          = $router->build($live_site . '/index.php?option=com_joomcck&view=cpanel&Itemid=' . $menu_table->id);

		return $url->toString();


	}

}


