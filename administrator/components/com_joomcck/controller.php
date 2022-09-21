<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

/**
 * Main Controller
 *
 * @package		Joomcck
 * @subpackage	com_joomcck
 * @since		6.0
 */
class JoomcckController extends MControllerBase
{

	public $model_prefix = 'JoomcckBModel';
	/**
	 * Method to display a view.
	 *
	 * @param	boolean			$cachable	If true, the view output will be cached
	 * @param	array			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	MController		This object to support chaining.
	 * @since	6.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$this->input->set('view', $this->input->get('view', 'about'));


		if(!$this->input->get('view'))
		{
			$this->setRedirect('index.php?option=com_joomcck&view=about');
		}

		if(!JComponentHelper::getParams('com_joomcck')->get('general_upload'))
		{
			JError::raiseWarning(400, JText::_('CUPLOADREQ'));
			$this->setRedirect('index.php?option=com_config&view=component&component=com_joomcck');
		}

		if(!JFolder::exists(JPATH_ROOT.'/media/mint/js'))
		{
			JError::raiseWarning(400, JText::_('CINSTALLMEDIAPACK'));
			$this->setRedirect('index.php?option=com_installer&view=install');
		}

		parent::display();

		return $this;
	}

	public function getModel($name = '', $prefix = 'JoomcckModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}
}