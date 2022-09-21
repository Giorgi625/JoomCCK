<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

class JoomcckTablePacks_sections extends JTable
{
	public function __construct(&$_db)
	{
		parent::__construct('#__js_res_packs_sections', 'id', $_db);
		
	
	}
	
	public function bind($data, $ignore = '')
	{
		if (!isset($data['ctime']))
		{
			$data['ctime'] = JFactory::getDate()->toSql();
		}
		
		$data['mtime'] = JFactory::getDate()->toSql();
		
		$params = JRequest::getVar('jform', array(), 'post', 'array');
		$params_types = JRequest::getVar('params', array(), 'post', 'array');
		if(isset($params['params']))
		{
			$result = array_merge($params['params'], $params_types);
			$registry = new JRegistry();
			$registry->loadArray($result);
			$data['params'] = (string)$registry;
		}
		
		return parent::bind($data, $ignore);
	}
	
}
?>
