<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

class JoomcckViewPacksection extends MViewBase
{
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item = $this->get('Item');

		$doc = \Joomla\CMS\Factory::getDocument();

		$this->form = $this->get('Form');

		if($this->item->id)
		{
			$this->parameters = MModelBase::getInstance('Packsection', 'JoomcckModel')->getSectionForm($this->item->section_id, $this->item->params);
		}

		// Check for errors.
		if(count($errors = $this->get('Errors')))
		{
			throw new Exception( implode("\n", $errors),500);

		}

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		\Joomla\CMS\Factory::getApplication()->input->set('hidemainmenu', true);

		$user		= \Joomla\CMS\Factory::getApplication()->getIdentity();
		$isNew		= ($this->item->id == 0);
// 		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		\Joomla\CMS\Toolbar\ToolbarHelper::title(($isNew ? \Joomla\CMS\Language\Text::_('CNEWPACKSECTION') : \Joomla\CMS\Language\Text::_('CEDITPACKSECTION')), 'sections');

// 		if (!$checkedOut)
		{
			\Joomla\CMS\Toolbar\ToolbarHelper::apply('packsection.apply', 'JTOOLBAR_APPLY');
			\Joomla\CMS\Toolbar\ToolbarHelper::save('packsection.save', 'JTOOLBAR_SAVE');
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('packsection.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}
		\Joomla\CMS\Toolbar\ToolbarHelper::cancel('packsection.cancel', 'JTOOLBAR_CANCEL');
		//MRToolBar::helpW('http://help.joomcoder.com/joomcck/index.html?filters.htm', 1000, 500);
	}
}
