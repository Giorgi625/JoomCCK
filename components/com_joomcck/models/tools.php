<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.modeladmin');
jimport('joomla.form.form');
jimport('mint.forms.formhelper');

class JoomcckModelTools extends JModelAdmin
{
	var $_tools = array();
	var $_form = array();

	function __construct()
	{
		$this->_id    = \Joomla\CMS\Factory::getApplication()->input->getInt('id');
		$this->option = 'com_joomcck';
		parent::__construct();
	}

	public function getTools()
	{
		if(empty($this->_tools))
		{
			$this->_fetchTools();
		}

		return $this->_tools;
	}

	public function getTool()
	{
		if(empty($this->_tools))
		{
			$this->_fetchTools();
		}

		$tool = $this->_tools[\Joomla\CMS\Factory::getApplication()->input->get('name')];
		$tool->name = \Joomla\CMS\Factory::getApplication()->input->get('name');

		return $tool;
	}

	public function getToolForm()
	{
		$tool = $this->getTool();

		$form_file = JPATH_ROOT . '/components/com_joomcck/library/php/tools/' . $tool->name . '/form.xml';
		$form_data = JPATH_ROOT . '/components/com_joomcck/library/php/tools/' . $tool->name . '/data.json';

        $form = '';
		if(is_file($form_file))
		{
			if(!is_file($form_data))
			{
				$a = '{}';
				\Joomla\Filesystem\File::write($form_data, $a);
			}

			$params = new \Joomla\Registry\Registry();
			$params->loadFile($form_data);

			$form_object = \Joomla\CMS\Form\Form::getInstance('joomccklools.form', $form_file, array('control' => 'jform'));
			$form        = MEFormHelper::renderFieldset($form_object, $tool->name, $params, NULL, FORM_STYLE_TABLE);
		}

        return $form;

        /*

		$dispatcher = \Joomla\CMS\Factory::getApplication();
		\Joomla\CMS\Plugin\PluginHelper::importPlugin('mint');
		$form = $dispatcher->triggerEvent('onToolGetForm', array(
			'com_joomcck.tools',	$form, null, null
		));

        return implode('', array_values($form));
        */
	}

	public function getForm($data = array(), $loadData = TRUE)
	{
		// Get the form.
		$form = $this->loadForm('com_joomcck.config', 'config', array(
			'control'   => 'jform',
			'load_data' => $loadData
		));
		if(empty($form))
		{
			return FALSE;
		}

		return $form;
	}
	private function _fetchTools()
	{
		$folders = glob(JPATH_ROOT . "/components/com_joomcck/library/php/tools/*");

		$tools = array();

		foreach($folders AS $key => $folder)
		{
			if(!is_dir($folder))
			{
				continue;
			}
			$tool = basename($folder);
			$meta = json_decode(file_get_contents($folder.'/meta.json'));

			$meta->id = substr(md5($key), 0, 5);
			$tools[$tool] = $meta;
		}

		$this->_tools = $tools;
	}
}