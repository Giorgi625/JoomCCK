<?php 
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
?>

<?php echo HTMLFormatHelper::layout('navbar'); ?>

<form action="<?php echo \Joomla\CMS\Uri\Uri::getInstance()->toString();?>" method="post" name="adminForm" id="adminForm">
	<div class="page-header">
		<h1>
			<img src="<?php echo \Joomla\CMS\Uri\Uri::root(TRUE); ?>/components/com_joomcck/images/icons/sections.png">
			<?php echo \Joomla\CMS\Language\Text::sprintf('CREDISPACKSECTIONS', $this->pack->name); ?>
		</h1>
	</div>

	<a href="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_joomcck&view=packs'); ?>" class="btn">
		<?php echo HTMLFormatHelper::icon('arrow-180.png'); ?>
		<?php echo \Joomla\CMS\Language\Text::_('COB_BACKTOPACKER'); ?>
	</a>
	<div class="clearfix"></div>
	<br/>

	<?php echo HTMLFormatHelper::layout('items'); ?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%"><input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" /></th>
				
				<th class="title">
					<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort',  'Name', 'name', $listDirn, $listOrder); ?>
				</th>

				<th width="1%" class="nowrap">
					<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort',  'ID', 'id', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php

		foreach($this->items as $i => $item):
			
			?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.id', $i, $item->id); ?>
				</td>
				<td nowrap="nowrap">
					<a href="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_joomcck&task=packsection.edit&id='.$item->id); ?>"><?php echo $item->name; ?></a>
				</td>
				<td class="center">
					<?php echo (int) $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<input type="hidden" name="pack_id" value="<?php echo $this->state->get('pack'); ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
</form>