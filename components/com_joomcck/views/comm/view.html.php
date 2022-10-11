<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('joomla.application.component.view');
class JoomcckViewComm extends MViewBase
{
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item = $this->get('Item');
		$this->form = $this->get('Form');
		
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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		
		JToolBarHelper::title(JText::_('CEDITCOMMENT'), 'comments.png');
		
		JToolBarHelper::apply('comment.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('comment.save', 'JTOOLBAR_SAVE');
		
		JToolBarHelper::cancel('comment.cancel', 'JTOOLBAR_CANCEL');
		//MRToolBar::helpW('http://help.JoomBoost.com/joomcck/index.html?filters.htm', 1000, 500);
		JToolBarHelper::divider();
	}
}