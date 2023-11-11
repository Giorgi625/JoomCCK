<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die;
jimport('mint.mvc.controller.admin');
class JoomcckControllerComments extends MControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		if(!$this->input)
		{
			$this->input = \Joomla\CMS\Factory::getApplication()->input;
		}
	}
	public function getModel($type = 'Comment', $prefix = 'JoomcckModel', $config = array())
	{
		return MModelBase::getInstance($type, $prefix, $config);
	}
	
	public function delete()
	{
		
		$cid	= $this->input->get('cid', array(), '', 'array');
		$cid = \Joomla\Utilities\ArrayHelper::toInteger($cid);

		\Joomla\CMS\Table\Table::addIncludePath(JPATH_ROOT . '/components/com_joomcck/tables');
		$comment = \Joomla\CMS\Table\Table::getInstance('Cobcomments', 'JoomcckTable');
		$comment->load($cid[0]);
		
		parent::delete();
		
		$model_record = MModelBase::getInstance('Record', 'JoomcckModel');
		$model_record->onComment($this->input->get('record_id'), get_class_vars($comment));
		
		$record = \Joomla\CMS\Table\Table::getInstance('Record', 'JoomcckTable');
		$record->load($this->input->getInt('record_id'));
		
		$url = 'index.php?option=com_joomcck&view=record';
		$url .= $this->getRedirectToListAppend();
		$this->setRedirect($url);
		if($comment->user_id)
		{
			
			$data = $comment->getProperties();
			$data['record'] = $record->getProperties();
			
			CEventsHelper::notify('record', CEventsHelper::_COMMENT_DELETED, $this->input->get('record_id'), $this->input->get('section_id'), 0, 0, 0, $data, 2, $comment->user_id);
		}
		ATlog::log($record, ATlog::COM_DELET, $comment->id);
	}
	
	public function publish()
	{
	    parent::publish();

        if($this->input->get('view') == 'comms') {
            $url = 'index.php?option=com_joomcck&view=comms&Itemid='.$this->input->get('Itemid');
        } else {
            $url = 'index.php?option=com_joomcck&view=record';
            $url .= $this->getRedirectToListAppend();
        }
        $this->setRedirect(\Joomla\CMS\Router\Route::_($url, FALSE));
		
		$task 	= $this->getTask();
		
		$cid	= $this->input->get('cid', array(), '', 'array');
		$cid = \Joomla\Utilities\ArrayHelper::toInteger($cid);
		
		$comment = \Joomla\CMS\Table\Table::getInstance('Cobcomments', 'JoomcckTable');
		$comment->load($cid[0]);
		
		$record = \Joomla\CMS\Table\Table::getInstance('Record', 'JoomcckTable');
		$record->load($comment->record_id);
			
		if($comment->user_id)
		{
			$data = $comment->getProperties();
			$data['record'] = $record->getProperties();
			
			CEventsHelper::notify('record', ($task == 'publish' ? CEventsHelper::_COMMENT_APPROVED : CEventsHelper::_COMMENT_UNPUBLISHED), $this->input->get('record_id'), $this->input->get('section_id'), 0, ($task == 'publish' ? $comment->id  : 0), 0, $data, 2, $comment->user_id);
		}
		
		ATlog::log($record, ($task == 'publish' ? ATlog::COM_PUBLISHED : ATlog::COM_UNPUBLISHED), $comment->id);

		$record->index();
		
	}
	
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		return $this->getRedirectToListAppend($recordId = null, $urlVar = 'id');
	}
	
	protected function getRedirectToListAppend($recordId = null, $urlVar = 'id')
	{
		
		
		$tmpl = $this->input->getCmd('tmpl');
		$record_id	= $this->input->get('record_id');
		$section_id	= $this->input->get('section_id');
		$cat_id	= $this->input->get('cat_id');
		$ucat_id	= $this->input->get('ucat_id');
		
		$append		= '';

		if ($tmpl) {
			$append .= '&tmpl='.$tmpl;
		}
		
		if ($record_id) {
			$append .= '&id='.$record_id;
		}
		if ($section_id) {
			$append .= '&section_id='.$section_id;
		}
		if ($cat_id) {
			$append .= '&cat_id='.$cat_id;
		}
		if ($ucat_id) {
			$append .= '&ucat_id='.$ucat_id;
		}
		
		return $append;
	}
	
}