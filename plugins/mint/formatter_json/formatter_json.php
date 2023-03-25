<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die('Restricted access');

class plgMintFormatter_json extends JPlugin
{
	private $tmpl_path;

	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
		$this->tmpl_path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tmpl' . DIRECTORY_SEPARATOR;
	}

	function onListFormat($view)
	{
		if (JFactory::getApplication()->input->get('formatter') != 'json') return;
		$this->sendHeader();
		$template = JFactory::getApplication()->input->get('template');
		require $this->tmpl_path . 'list' . DIRECTORY_SEPARATOR . ($template ? $template . ".php" : $this->params->get('tmpl_list', 'json.php'));
	}

	function onRecordFormat($view)
	{
		if (JFactory::getApplication()->input->get('formatter') != 'json') return;
		$this->sendHeader();
		$template = JFactory::getApplication()->input->get('template');
		require $this->tmpl_path . 'record' . DIRECTORY_SEPARATOR . ($template ? $template . ".php" : $this->params->get('tmpl_full', 'json.php'));
	}

	function sendHeader()
	{
		header('Content-Type: application/json');
	}
}