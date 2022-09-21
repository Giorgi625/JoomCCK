<?php
/**
 * Joomcck by JoomBoost
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomBoost.com/
 * @copyright Copyright (C) 2012 JoomBoost (https://www.joomBoost.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die('Restricted access');
?>

<?php echo HTMLFormatHelper::layout('navbar'); ?>

<form action="<?php echo JUri::getInstance()->toString() ?>" method="post" id="adminForm" name="adminForm" class="form-horizontal">
    <?php echo HTMLFormatHelper::layout('item', ['nosave' => 1, 'task_ext' => 'chco']); ?>
    <div class="page-header">
        <h1>
            <img src="<?php echo JUri::root(TRUE); ?>/components/com_joomcck/images/icons/items.png">
            <?php echo JText::_('C_MASS_CORE_FIELDS'); ?>
        </h1>
    </div>

    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('published'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('published'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('user_id'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('user_id'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('access'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('meta_index'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('meta_index'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('meta_descr'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('meta_descr'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('meta_key'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('meta_key'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('langs'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('langs'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('featured'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('ftime'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('ftime'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('ctime'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('ctime'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('extime'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('extime'); ?></div>
    </div>
    <div class="control-group">
        <div class="control-label"><?php echo $this->form->getLabel('mtime'); ?></div>
        <div class="controls"><?php echo $this->form->getInput('mtime'); ?></div>
    </div>

    <?php foreach($this->cid AS $id): ?>
        <input type="hidden" name="cid[]" value="<?php echo $id ?>"/>
    <?php endforeach; ?>
    <input type="hidden" name="task" value=""/>
    <?php echo JHtml::_('form.token'); ?>
</form>