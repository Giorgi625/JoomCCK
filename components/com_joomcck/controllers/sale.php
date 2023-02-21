<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die();
jimport('mint.mvc.controller.form');
class JoomcckControllerSale extends MControllerForm
{

	public function __construct($config = array())
	{
		parent::__construct($config);
		
		if(!$this->input)
		{
			$this->input = JFactory::getApplication()->input;
		}
		$this->view_item = 'elements';
		$this->view_list = 'elements';
	}

	public function postSaveHook(MModelBase $model, $validData = array())
	{
		$order_id = $record_id = $model->getState('sale.id');

		//echo $validData['record_id']; exit;

		if(!$order_id || !$validData['record_id'])
		{
			return ;
		}

		$order = JTable::getInstance('Sales', 'JoomcckTable');
		$order->load($order_id);

		$field = JTable::getInstance('Field', 'JoomcckTable');
		$field->load($order->field_id);
		$field->params = new JRegistry($field->params);

		if($field->params->get('params.new_sale_manual'))
		{
			CEventsHelper::notify('record', CEventsHelper::_FIELDS_PAY_NEW_SALE_MANUAL, $order->record_id,
				$order->section_id, 0, 0, $order->field_id, $order, 2, $order->user_id);
		}
	}


	protected function allowSave($data = array(), $key = 'id')
	{
		return TRUE;
	}

	protected function allowAdd($data = array(), $key = 'id')
	{
		$user = JFactory::getUser();
		$allow = $user->authorise('core.create', 'com_joomcck.sale');

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
		return JFactory::getUser()->authorise('core.edit', 'com_joomcck.sale');
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		return '&layout=addsale';
	}
	protected function getRedirectTolistAppend()
	{
		return '&layout=saler';
	}

	public function delete()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_joomcck&view=elements&layout=saler', FALSE));

		$user = JFactory::getUser();

		if(!$user->get('id'))
		{
			Factory::getApplication()->enqueueMessage(JText::_('AJAX_PLEASELOGIN'),'warning');
			CCommunityHelper::goToLogin();
		}

		$id = $this->input->getInt('id');

		$table = JTable::getInstance('Sales', 'JoomcckTable');
		$table->load($id);

		if($table->gateway && $table->gateway != 'CMANUAL')
		{

			Factory::getApplication()->enqueueMessage(JText::_('CCANNOTDELETEORDER'),'warning');
			return;
		}

		if(!$this->isSuperUser() && ($user->get('id') != $table->saler_id))
		{
			Factory::getApplication()->enqueueMessage(JText::_('CNOPERMISION'),'warning');
			return;
		}

		$table->delete();

		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('CORDERDELETEDSUCCESS'));
	}


	protected function getReturnPage()
	{


		$return = $this->input->get('return', null);

		if(empty($return) || ! JUri::isInternal(JoomcckFilter::base64($return)))
		{
			return JURI::base();
		}
		else
		{
			return JoomcckFilter::base64($return);
		}
	}

	private  function isSuperUser()
	{
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$active = $menu->getActive();
		$allow_users = $active->params->get('allow_users', false);
		$user_ids = explode(',', $allow_users);

		\Joomla\Utilities\ArrayHelper::toInteger($user_ids);
		ArrayHelper::clean_r($user_ids);

		return (in_array(JFactory::getUser()->get('id'), $user_ids));
	}

	public function clean()
	{
		$app = JFactory::getApplication();
		$clean = $this->input->get('clean', array(), 'array');
		foreach($clean as $name => $val)
		{
			if($val)
			{
				$name = str_replace('_', '.', $name);
				$app->setUserState('com_joomcck.products.' . $name, NULL);
			}
		}

		$url = 'index.php?option=com_joomcck&view=elements&layout=products&tmpl=component';
		$this->setRedirect(JRoute::_($url, FALSE));
	}
}