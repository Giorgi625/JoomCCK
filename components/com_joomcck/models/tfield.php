<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();
jimport('mint.mvc.model.admin');

class JoomcckModelTfield extends MModelAdmin
{
	public function __construct($config = array())
	{
		$this->option = 'com_joomcck';
		parent::__construct($config);
	}

	public function populateState($ordering = NULL, $direction = NULL)
	{
		$app = JFactory::getApplication('administrator');

		$type = $app->getUserStateFromRequest('com_joomcck.tfields.filter.type', 'type_id', 0, 'int');
		$this->setState('filter.type', $type);

		parent::populateState($ordering = NULL, $direction = NULL);
	}

	public function getTable($type = 'Field', $prefix = 'JoomcckTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getFieldForm($field_type, $default = array())
	{
		$file = JPATH_ROOT . '/components/com_joomcck/fields' . DIRECTORY_SEPARATOR . $field_type . DIRECTORY_SEPARATOR . $field_type . '.xml';
		if(!JFile::exists($file))
		{
			echo "File not found: {$file}";
		}

		FieldHelper::loadLang($field_type);

		$form = new JForm('params', array(
			'control' => 'params'
		));

		$form->loadFile($file, TRUE, 'config');

		return MFormHelper::renderGroup($form, $default, 'params');
	}

	public function getForm($data = array(), $loadData = TRUE)
	{
		$app = JFactory::getApplication();

		$form = $this->loadForm('com_joomcck.tfield', 'field', array(
			'control'   => 'jform',
			'load_data' => $loadData
		));
		if(empty($form))
		{
			return FALSE;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_joomcck.edit.tfield.data', array());

		if(empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	public function getItem($pk = NULL)
	{
		if($item = parent::getItem($pk))
		{
			//print_r($item->params);


			if(!is_array($item->params))
			{
				//echo $item->params;
				$registry = new JRegistry();
				$registry->loadString($item->params);
				$item->params = $registry->toArray();
			}
		}

		if(JFactory::getApplication()->input->getInt('group'))
		{
			$item->group_id = JFactory::getApplication()->input->getInt('group');
		}

		return $item;
	}

	protected function getReorderConditions($table)
	{
		return array('group_id = ' . $table->group_id, 'type_id = ' . $table->type_id);
	}

	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		return $user->authorise('core.delete', 'com_joomcck.tfield.' . (int)$record->id);
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		return $user->authorise('core.edit.state', 'com_joomcck.tfield.' . (int)$record->id);
	}

	public function changeState($task, &$pks, $value = 1)
	{
		$dispatcher = JFactory::getApplication();
		$user       = JFactory::getUser();
		$table      = $this->getTable();
		$pks        = (array)$pks;


		// Access checks.
		foreach($pks as $i => $pk)
		{
			$table->reset();

			if($table->load($pk))
			{
				if(!$this->canEditState($table))
				{
					// Prune items that you can't change.
					unset($pks[$i]);
					JLog::add(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), JLog::WARNING, 'jerror');

					return FALSE;
				}
			}
		}

		$params = new JRegistry($table->params);
		$param  = str_replace('not', '', $task);
		$params->set('core.' . $param, $value);

		$table->params = $params->toString();
		$table->store();

		// Clear the component's cache
		$this->cleanCache();

		return TRUE;
	}
}