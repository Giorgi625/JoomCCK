<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();

jimport('mint.mvc.controller.form');

class JoomcckControllerPack extends MControllerForm
{

	public $model_prefix = 'JoomcckBModel';

	public function __construct($config = array())
	{
		parent::__construct($config);

		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}
	}

	public function getModel($name = '', $prefix = 'JoomcckModel', $config = array())
	{
		return parent::getModel($name, $prefix, $config);
	}

	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$allow = $user->authorise('core.create', 'com_joomcck.packs');

		if($allow === null)
		{
			return parent::allowAdd($data);
		}
		else
		{
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		return JFactory::getUser()->authorise('core.edit', 'com_joomcck.packs');
	}

	public function build()
	{
		$model = $this->getModel();
		$pack_id = $this->input->getInt('pack_id', 0);
		$msg = '';
		if($model->build($pack_id))
		{
			$msg = JText::_('CBUILDCREATED');
		}

		$this->setRedirect('index.php?option=com_joomcck&view=packs', $msg);
	}
}