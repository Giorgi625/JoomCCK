<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

use Joomcck\Layout\Helpers\Layout;

defined('_JEXEC') or die('Restricted access');

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip', '*[rel^="tooltip"]');

$user = \Joomla\CMS\Factory::getApplication()->getIdentity();
$userId = $user->get('id');

$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');

\Joomla\CMS\HTML\HTMLHelper::_('formbehavior.chosen', '.select');
?>

<?php echo HTMLFormatHelper::layout('navbar'); ?>


<div class="page-header">
    <h1>
        <img src="<?php echo \Joomla\CMS\Uri\Uri::root(TRUE); ?>/components/com_joomcck/images/icons/comments.png">
		<?php echo \Joomla\CMS\Language\Text::_('CCOMMENTS'); ?>
    </h1>
</div>
<?php echo HTMLFormatHelper::layout('items', $this); ?>
<div class="clearfix"></div>


<form action="<?php echo $this->action ?>" method="post" name="adminForm" id="adminForm">
    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
				    <?php echo HTMLFormatHelper::layout('search', $this); ?>
                </div>
			    <?php echo Layout::render('admin.list.ordering', $this) ?>
            </div>

            <div class="my-2">
			    <?php echo HTMLFormatHelper::layout('filters', $this); ?>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover" id="articleList">
                <thead>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo \Joomla\CMS\Language\Text::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                </th>
                <th class="">
				    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CSUBJECT', 'a.comment', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="nowrap">
				    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CUSER', 'u.username', $listDirn, $listOrder); ?>
                </th>
                <th width="1%" class="nowrap">
				    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                </th>
                <th width="8%" class="nowrap">
				    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CCREATED', 'a.ctime', $listDirn, $listOrder); ?>
                </th>
                <th width="1%">
				    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'ID', 'a.id', $listDirn, $listOrder); ?>
                </th>
                </thead>
                <tbody>
			    <?php foreach($this->items as $i => $item) : ?>
				    <?php
				    $canCheckin = TRUE;
				    $canChange  = TRUE;
				    $body       = substr(strip_tags($item->comment), 0, 100);
				    ?>

                    <tr class="row<?php echo $i % 2; ?>">
                        <td>
						    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td>
                            <a href="javascript:void(0);" rel="tooltip" data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_('CFILTERRECORD'); ?>"
                               onclick="document.getElementById('filter_search').value='record:<?php echo $item->record_id; ?>'; document.adminForm.submit();">
							    <?php echo $item->record ?>
                            </a>
                            [<a href="#" rel="tooltip" data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_('CFILTERBYTYPE'); ?>" onclick="Joomcck.setAndSubmit('filter_type', <?php echo $item->type_id ?>)"><?php echo $this->escape($item->type); ?></a>]
                            <br/>
                            <small>
                                <a href="index.php?option=com_joomcck&task=comm.edit&id=<?php echo (int)$item->id; ?>" rel="tooltip" data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_('CEDITCOMMENT'); ?>">
								    <?php echo $body; ?>
                                </a>
                            </small>
                        </td>
                        <td width="5%" nowrap="nowrap">
                            <small>
							    <?php
							    $user = \Joomla\CMS\Factory::getUser($item->user_id);
							    $link = 'index.php?option=com_users&task=edit&cid[]=' . $user->get('id');

							    if(is_dir(JPATH_ADMINISTRATOR . '/components/com_juser'))
							    {
								    $link = 'index.php?option=com_juser&task=edit&cid[]=' . $user->get('id');
							    }
							    ?>
							    <?php if($user->get('username')): ?>
								    <?php echo \Joomla\CMS\HTML\HTMLHelper::link('javascript:void(0);', $user->get('username'), array(
									    'rel' => "tooltip", 'data-bs-original-title' => \Joomla\CMS\Language\Text::_('CFILTERUSER'), 'onclick' => 'document.getElementById(\'filter_search\').value=\'user:' . $item->user_id . '\'; document.adminForm.submit();'
								    )) ?>
								    <?php //echo \Joomla\CMS\HTML\HTMLHelper::_('ip.block_user', $item->user_id, $item->id);?>
							    <?php else: ?>
								    <?php echo $item->name ? $item->name . " (<a href=\"javascript:void(0);\" rel=\"tooltip\" data-bs-original-title=\"" . \Joomla\CMS\Language\Text::_('CFILTEREMAIL') . "\" onclick=\"document.getElementById('filter_search').value='email:{$item->email}'; document.adminForm.submit();\">{$item->email}</a>) " : \Joomla\CMS\Language\Text::_('CANONYMOUS') ?>
							    <?php endif; ?>

							    <?php if($item->ip): ?>
                                    <div>
									    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('ip.country', $item->ip); ?>
									    <?php echo \Joomla\CMS\HTML\HTMLHelper::link('javascript:void(0);', $item->ip, array(
										    'rel' => "tooltip", 'data-bs-original-title' => \Joomla\CMS\Language\Text::_('CFILTERIP'), 'onclick' => 'document.getElementById(\'filter_search\').value=\'ip:' . $item->ip . '\'; document.adminForm.submit();'
									    )); ?>
									    <?php //echo \Joomla\CMS\HTML\HTMLHelper::_('ip.block_ip', $item->ip, $item->id);?>
                                    </div>
							    <?php endif; ?>
                            </small>
                        </td>

                        <td align="center"><?php echo \Joomla\CMS\HTML\HTMLHelper::_('jgrid.published', $item->published, $i, 'comments.', $canChange); ?></td>

                        <td align="center" class="nowrap">
                            <small>
							    <?php $data = new \Joomla\CMS\Date\Date($item->ctime);
							    echo $data->format(\Joomla\CMS\Language\Text::_('CDATE1')); ?>
                            </small>
                        </td>

                        <td align="center">
                            <small><?php echo $item->id; ?></small>
                        </td>
                    </tr>
			    <?php endforeach; ?>
                </tbody>
            </table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
		    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
        </div>
        <div class="card-footer">
		    <?php echo Layout::render('admin.list.pagination', ['pagination' => $this->pagination]) ?>
        </div>
    </div>

</form>