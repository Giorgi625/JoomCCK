<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();
jimport('mint.mvc.model.list');

class JoomcckModelPacks extends MModelList
{
	public function __construct($config = array())
	{
		if (empty ( $config ['filter_fields'] ))
		{
			$config ['filter_fields'] = array ('id', 'name', 'version', 'ctime', 'mtime', 'btime');
		}
		$this->option = 'com_joomcck';
		parent::__construct ( $config );
	}

	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		$query->select('p.*');
		$query->from('#__js_res_packs AS p');

		$query->select('count(ps.section_id) AS secnum');
		$query->leftJoin('#__js_res_packs_sections AS ps ON p.id = ps.pack_id');


		$orderCol	= $this->state->get('list.ordering', 'ctime');
		$orderDirn	= $this->state->get('list.direction', 'asc');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		$query->group('p.id');

// 		echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		settype($items, 'array');
		$path = JPATH_CACHE.DIRECTORY_SEPARATOR;
		foreach ($items AS $item)
		{
			$filename = 'pack_joomcck.' . \Joomla\CMS\Filter\OutputFilter::stringURLSafe($item->name) . '('.str_replace('pack', '', $item->key).').j3.v.9.' . ($item->version) . '.zip';
			if(\Joomla\CMS\Filesystem\File::exists($path.$filename))
			{
				$item->download = '<a href="'.JURI::root(TRUE).'/cache/'.$filename.'">'.$filename.'</a>';
				$item->size = HTMLFormatHelper::formatSize(filesize($path.$filename));
			}
			else
			{
				$item->download = \Joomla\CMS\Language\Text::_('CNOBUILD');
				$item->size = '--';
			}

		}

		return $items;
	}
}
