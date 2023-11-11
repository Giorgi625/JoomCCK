<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 *
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license   GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
defined('_JEXEC') or die();

$params  = $this->params;
$default = $this->value;
$labels  = $this->labels;

if(!$default)
{
	$default[] = '';
}
$readonly = ($default && $this->params->get('params.label_change')) ? '' : 'readonly';
?>

<div id="url-list<?php echo $this->id; ?>"></div>
<div class="clearfix"></div>
<button class="btn btn-outline-success" type="button" id="add-url<?php echo $this->id; ?>">
	<i class="fas fa-plus"></i>
	<?php echo \Joomla\CMS\Language\Text::_('U_ADDURL'); ?>
</button>


<script type="text/javascript">
	var URL<?php echo $this->id;?> = new joomcckUrlField({
		limit: <?php echo (int)($params->get('params.limit') ? $params->get('params.limit') : 0);?>,
		limit_alert: '<?php echo addslashes(\Joomla\CMS\Language\Text::sprintf("U_REACHEDLIMIT", (int)($params->get('params.limit') ? $params->get('params.limit') : 1)));?>',
		id: <?php echo $this->id;?>,
		labels: <?php echo (int)$this->params->get('params.label');?>,
		labels_change: <?php echo (int)$this->params->get('params.label_change');?>,
		default_labels: new Array('<?php echo implode("','", $labels)?>'),
		label1: '<?php echo \Joomla\CMS\Language\Text::_('U_URL');?>',
		label2: '<?php echo \Joomla\CMS\Language\Text::_('U_LABEL');?>'
	});
	<?php foreach($default as $i => $url_): ?>
	<?php
	$label = !empty($default[$i]['label']) ? $default[$i]['label'] : @$labels[$i];
	$url = !empty($default[$i]['url']) ? $default[$i]['url'] : '';
	$hits = !empty($default[$i]['hits']) ? $default[$i]['hits'] : '';
	?>
	jQuery(document).ready(function() {
		URL<?php echo $this->id;?>.createBlock('<?php echo $url;?>', '<?php echo $label;?>', '<?php echo $hits  ?>');
	});
	<?php endforeach; ?>
</script>