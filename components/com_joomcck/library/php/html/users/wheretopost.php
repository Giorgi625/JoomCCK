<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die(); ?>
<div id="posts-list">
  <?php foreach($default AS $u): ?>
    <div class="alert alert-info">
      <a class="close" data-dismiss="alert" href="#">&times;</a>
      <?php echo $u->id == $user->get('id') ? \Joomla\CMS\Language\Text::_('CPOSTHOMEPAGE') : \Joomla\CMS\Language\Text::sprintf('CPOSTHOMEPAGEUSER', CCommunityHelper::getName($u->id, \Joomla\CMS\Factory::getApplication()->input->getInt('section_id')));?>
      <?php if($u->params->get('sections.'.\Joomla\CMS\Factory::getApplication()->input->getInt('section_id').'.title')):?>
        <br /><small><?php echo $u->params->get('sections.'.\Joomla\CMS\Factory::getApplication()->input->getInt('section_id').'.title');?></small>
      <?php endif;?>
      <input type="hidden" name="wheretopost[<?php echo $u->id;?>]" value="<?php echo $u->id;?>">
    </div>
  <?php endforeach;?>
</div>

<a href="#wheretopost" class="btn-sm btn-light border" data-toggle="modal" role="button"><?php echo \Joomla\CMS\Language\Text::_('CSELECTWTP');?></a>
  
  
<div style="width:700px;" class="modal hide fade" id="wheretopost" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"><?php echo \Joomla\CMS\Language\Text::_('CCHOSETOPOST');?></h3>
  </div>
  <div class="modal-body" style="overflow-x: hidden; max-height:500px; padding:0;">
    <iframe frameborder="0" width="100%" height="410px" src="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_joomcck&view=elements&layout=homepages&tmpl=component&section_id='.\Joomla\CMS\Factory::getApplication()->input->getInt('section_id').'&record_id='.$record->id)?>"></iframe>
  </div>
  <div class="modal-footer">
    <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true"><?php echo \Joomla\CMS\Language\Text::_('CCLOSE');?></button>
  </div>
</div>
<script>

function choosewheretopost(id, text)
{
  if(jQuery('input[name^="wheretopost\\[' + id + '\\]"]').val()) {
	  jQuery("#wheretopost").modal("toggle");
    return;
  }

  jQuery("#posts-list").append('<div class="alert alert-info"><a class="close" data-dismiss="alert" href="#">&times;</a>' + text + '<input type="hidden" name="wheretopost[' + id + ']" value="' + id + '"></div>');
  jQuery("#wheretopost").modal("toggle");
}
</script>