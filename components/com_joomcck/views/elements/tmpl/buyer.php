<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die();

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip', '*[rel^="tooltip"]');

?>

<?php
$user      = \Joomla\CMS\Factory::getApplication()->getIdentity();
$userId    = $user->get('id');
$colors    = array(
	1 => '721111',
	2 => 'ff0000',
	3 => '121896',
	4 => 'e59112',
	5 => '0c8c0e',
);
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$alert     = \Joomla\CMS\Language\Text::_('CMAKESELECTION');
$links     = $this->pagination->getPagesLinks();

HTMLHelper::_('bootstrap.collapse');

?>


<div class="page-header"><h1><?php echo \Joomla\CMS\Language\Text::_('CMYORDERHIST') ?></h1></div>

<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_joomcck&view=elements&layout=buyer'); ?>" method="post"
      name="adminForm">

    <div class="controls controls-row">
        <div class="input-append">
            <input class="col-md-3" type="text" name="filter_search" id="filter_search"
                   value="<?php echo $this->state->get('filter.search'); ?>"/>
            <button class="btn" type="submit" rel="tooltip"
                    data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER_SUBMIT'); ?>">
				<?php echo HTMLFormatHelper::icon('magnifier.png'); ?>
            </button>
			<?php if ($this->state->get('filter.search')) : ?>
                <button class="btn<?php echo($this->state->get('filter.search') ? ' btn-warning' : null); ?>"
                        type="button"
                        onclick="Joomcck.setAndSubmit('filter_search', '');" rel="tooltip"
                        data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_('JSEARCH_FILTER_CLEAR'); ?>">
					<?php echo HTMLFormatHelper::icon('eraser.png'); ?>
                </button>
			<?php endif; ?>
            <button class="btn<?php if ($this->state->get('filter.section') || $this->state->get('filter.status')) echo ' btn-warning'; ?>"
                    type="button" data-bs-toggle="collapse" data-bs-target="#filters-block">
				<?php echo HTMLFormatHelper::icon('funnel.png'); ?>
            </button>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="collapse btn-toolbar" id="filters-block">
        <div class="well well-small">
            <select name="filter_status" style="max-width:150px;" onchange="this.form.submit()">
                <option value=""><?php echo \Joomla\CMS\Language\Text::_('JOPTION_SELECT_PUBLISHED'); ?></option>
				<?php echo \Joomla\CMS\HTML\HTMLHelper::_('select.options', $this->statuses, 'value', 'text', $this->state->get('filter.status'), true); ?>
            </select>

			<?php if (count($this->filter_sections) > 1): ?>
                <select name="filter_section" style="max-width:150px;" onchange="this.form.submit()">
                    <option value=""><?php echo \Joomla\CMS\Language\Text::_('CSELECTSECTION'); ?></option>
					<?php echo \Joomla\CMS\HTML\HTMLHelper::_('select.options', $this->filter_sections, 'value', 'text', $this->state->get('filter.section'), true); ?>
                </select>
			<?php endif; ?>
        </div>
    </div>
    <div class="clearfix"></div>

    <table class="table table-striped">
        <thead>
        <!--
			<th width="1%"><input type="checkbox" name="checkall-toggle" value=""
				onclick="checkAll(this)" /></th>
			</th>
			<th width="1%">
				<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'ID', 'o.id', $listDirn, $listOrder); ?>
			</th>
			 -->
        <th>
			<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CONAME', 'o.name', $listDirn, $listOrder); ?>
        </th>
        <th nowrap="nowrap" width="1%">
			<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CAMOUNT', 'section', $listDirn, $listOrder); ?>
        </th>
        <th width="1%" nowrap="nowrap">
			<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CCREATED', 'o.ctime', $listDirn, $listOrder); ?>
        </th>
        <th width="1%" nowrap="nowrap">
			<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CMODIFIED', 'o.mtime', $listDirn, $listOrder); ?>
        </th>
        <th width="1%" nowrap="nowrap">
			<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CSTATUS', 'o.status', $listDirn, $listOrder); ?>
        </th>
        </thead>
        <tbody>
		<?php foreach ($this->orders as $i => $order): ?>
            <tr class=" <?php echo $k = 1 - @$k ?>">
                <td>
					<span>
						<?php if ($order->field): ?>
                            <button type="button" class="btn btn-micro" data-toggle="collapse"
                                    data-target="#field<?php echo $order->id ?>"><i
                                        class="icon-arrow-down-3"></i></button>
						<?php endif; ?>

						<a href="<?php echo Url::record($order->record_id) ?>">
							<?php echo $order->name ?></a>

						<?php echo CEventsHelper::showNum('record', $order->record_id); ?>
					</span>
                    <div class="clearfix"></div>


                    <small>
                        <span><?php echo \Joomla\CMS\Language\Text::_('ID') ?>: <?php echo $order->id ?> | </span>
                        <span><?php echo $order->gateway; ?>: <?php echo $order->gateway_id ?> | </span>
                        <span><?php echo \Joomla\CMS\Language\Text::_('CSECTION') ?>: <?php echo $order->section ?> </span>
                    </small>

					<?php if ($order->comment): ?>
                        <p><?php echo $order->comment; ?></p>
					<?php endif; ?>

					<?php if ($order->field): ?>
                        <div class="field-data collapse" id="field<?php echo $order->id ?>">
                            <br><?php echo $order->field; ?></div>
					<?php endif; ?>
                </td>

                <td nowrap="nowrap"><strong><?php echo $order->amount ?><?php echo $order->currency ?></strong></td>
                <td><?php echo \Joomla\CMS\HTML\HTMLHelper::_('date', $order->ctime, 'Y/m/d'); ?></td>
                <td><?php echo \Joomla\CMS\HTML\HTMLHelper::_('date', $order->mtime, 'Y/m/d'); ?></td>
                <td style="color:#<?php echo $colors[$order->status] ?>"><?php echo $this->stat[$order->status]; ?></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination float-end">
		<?php echo $this->pagination->getPagesCounter(); ?>
		<?php echo $this->pagination->getLimitBox(); ?>
    </div>

    <div class="float-start pagination">
		<?php echo $this->pagination->getPagesLinks(); ?>
    </div>

    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="boxchecked" value="0"/>
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
</form>
