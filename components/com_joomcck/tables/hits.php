<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined( '_JEXEC' ) or die( 'Restricted access');

class JoomcckTablehits extends JTable
{
	public function __construct( &$_db ) {
		parent::__construct( '#__js_res_hits', 'id', $_db );
	}
	
	public function check()
	{
		$user = JFactory::getUser();
		
		$this->ctime = JFactory::getDate()->toSql();
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->user_id = $user->get('id');
		
		return TRUE;
	}
	
}
?>
