<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport( 'joomla.filesystem.folder' );
jimport( 'joomla.filesystem.file'   );

// Include the component HTML helpers.
\Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT.'/helpers/html');

class JFormFieldCpacksections extends JFormField
{
	public $type = 'Cpacksections';
	
	public function getInput()
	{
		$db = \Joomla\CMS\Factory::getDbo();
		if(!\Joomla\CMS\Factory::getApplication()->input->getInt('id' , 0))
		{
			$app = \Joomla\CMS\Factory::getApplication();
			$pack_id = $app->getUserStateFromRequest('com_joomcck.packsections.pack', 'pack_id');
			
			$db->setQuery('SELECT section_id FROM #__js_res_packs_sections WHERE pack_id = '.$pack_id);
			
			$ids = $db->loadColumn();
			
			$ids[] = 0;
			$db->setQuery('SELECT id as value, name as text FROM #__js_res_sections WHERE published = 1 AND id NOT IN ('.implode(',', $ids).') ORDER BY name ASC');
			
			$options = $db->loadObjectList();
			
			array_unshift($options, JHTML::_('select.option', '', \Joomla\CMS\Language\Text::_('CSELECTSECTION')));
			
			$html = \Joomla\CMS\HTML\HTMLHelper::_('select.genericlist', $options, 'jform[section_id]', 'onchange="changeSection(this.value);" class="form-select required"');
		}
		else
		{
			$db->setQuery('SELECT name FROM #__js_res_sections WHERE published = 1 AND id ='. $this->value);
			$sec = $db->loadObject();
			$html = '<input type="text" value="'.$sec->name.'" readonly="readonly" class="readonly"/><input type="hidden" value="'.$this->value.'" name="jform[section_id]"/>';
		}
		
		return $html;
	}
	
}
