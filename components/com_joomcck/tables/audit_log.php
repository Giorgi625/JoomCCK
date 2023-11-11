<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 *
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.table.table');
jimport('legacy.access.rules');

/**
 * @package JCommerce
 */
class JoomcckTableAudit_log extends \Joomla\CMS\Table\Table
{

	public function __construct(&$_db)
	{
		parent::__construct('#__js_res_audit_log', 'id', $_db);
	}

}