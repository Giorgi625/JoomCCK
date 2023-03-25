<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('JPATH_PLATFORM') or die();

jimport('joomla.html.html');
jimport('joomla.form.formfield');
JFormHelper::loadFieldClass('melist');

class JFormFieldMeresourcescategory extends JFormMEFieldList
{
	public $type = 'Meresourcescategory';
	
	protected function getOptions()
	{
		
		JHtml::addIncludePath(JPATH_ROOT. DIRECTORY_SEPARATOR .'administrator'. DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_joomcck'. DIRECTORY_SEPARATOR .'library'. DIRECTORY_SEPARATOR .'php'. DIRECTORY_SEPARATOR .'html');
		$sections = JHtml::_('joomcck.sections');
	
		$options = array();
		if ($this->element['select'] == 1)
		{
			$options[] = JHTML::_('select.option', '', JText::_('Selet Category'));
		}
		foreach ($sections as $type)
		{
			$options[] = JHTML::_('select.option', $type->value, $type->text);

		}
		return $options;

	}
}
?>