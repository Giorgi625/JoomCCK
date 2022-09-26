<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldSectiontypes extends JFormFieldList
{
	protected $type = 'sectiontypes';

	protected function getOptions()
	{
		$options[] = JHtml::_('select.option', '', JText::_('CINHERIT'));
		$options[] = JHtml::_('select.option', 'none', JText::_('CDONOTSHOWPOSTBUTTON'));
		
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		
		$section = ItemsStore::getSection($app->input->get('section_id'));
		$section->params =  new JRegistry($section->params);
		$types = $section->params->get('general.type');
		
		ArrayHelper::clean_r($types);
		\Joomla\Utilities\ArrayHelper::toInteger($types);
		$types[] = 0;
		
		$query = $db->getQuery(TRUE);
		$query->select('t.id, t.name, t.params');
		$query->from('#__js_res_types AS t');
		$query->where('t.id IN(' . implode(',', $types) . ')');
		$query->where('t.published = 1');
		
		
		$db->setQuery($query);
		$types = $db->loadObjectList();
		
		foreach ($types AS $type)
		{
			$options[] = JHtml::_('select.option', $type->id, $type->name);
		}

		return $options;
	}
}