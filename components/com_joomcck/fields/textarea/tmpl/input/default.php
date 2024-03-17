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
$params = $this->params;

\Joomla\CMS\HTML\HTMLHelper::_('bootstrap.tooltip', '*[rel^="tooltip"]');

$class      = ' class="form-control expanding' . $params->get('core.field_class') . ($this->required ? ' required' : NULL) . '"';
$required   = $this->required ? ' required="true" ' : NULL;
$max_length = (int)$params->get('params.maxlen', 0);
$js         = '';
?>

<?php if($params->get('params.notify', 0) == 1 && $max_length > 0) :
	$symbols_left = $max_length - \Joomla\String\StringHelper::strlen($this->value);
	$symbols_left = $symbols_left < 0 ? 0 : $symbols_left;
	?>
	<p class="text-success mb-2" id="alert-limit-<?php echo $this->id ?>">
		<small><?php echo \Joomla\CMS\Language\Text::sprintf($params->get('params.symbols_left_msg', 'TA_SYMBOLSLEFTMSG'), '<b><span id="lim_' . $this->id . '">' . $symbols_left . '</span></b>', $max_length); ?></small>
	</p>
	<script type="text/javascript">
		function plg_textarea_truncate<?php echo $this->id;?>(elem) {


			var maxSize = <?php echo $max_length?>;
			if(elem.value.length > maxSize) {
				elem.value = elem.value.substr(0, maxSize);
				jQuery('#alert-limit-<?php echo $this->id ?>').removeClass('text-success').addClass('text-danger');
			}
			else {
				jQuery('#alert-limit-<?php echo $this->id ?>').removeClass('text-danger').addClass('text-success');
			}
			var symbolsLeft = maxSize - elem.value.length;
			symbolsLeft = symbolsLeft < 0 ? 0 : symbolsLeft;
			jQuery('#lim_<?php echo $this->id; ?>').html(symbolsLeft)
		}
	</script>
	<?php
	$js = 'onkeyup="plg_textarea_truncate' . $this->id . '(this);"';
	?>
<?php endif; ?>

<?php if($params->get('params.bbcode', 0) && $params->get('params.bbcode_menu', 0)):
	\Joomla\CMS\Factory::getDocument()->addScript(\Joomla\CMS\Uri\Uri::root(TRUE) . '/components/com_joomcck/fields/textarea/assets/bbeditor.js');
	?>
	<div id="controls_<?php echo $this->id; ?>">
		<div class="btn-group float-start">
			<button type="button" class="btn btn-sm btn-light border"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCODEBOLD"); ?></button>
			<button type="button" class="btn btn-sm btn-light border"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCODEITALIC"); ?></button>
			<button type="button" class="btn btn-sm btn-light border"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCODEUNDERLINE"); ?></button>

			<button type="button" rel="tooltip" data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_("TA_BBCODEURL"); ?>"
					class="btn btn-sm btn-light border"><i class="fas fa-link"></i></button>
			<button type="button" rel="tooltip" data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_("TA_BBCODEIMG"); ?>"
					class="btn btn-sm btn-light border"><i class="fas fa-image"></i></button>
			<button type="button" rel="tooltip" data-bs-original-title="<?php echo \Joomla\CMS\Language\Text::_("TA_BBCODECODE"); ?>"
					class="btn btn-sm btn-light border"><i class="fas fa-code"></i></button>
		</div>
		<div class="float-end form-inline">
			<label><?php echo \Joomla\CMS\Language\Text::_("Font size"); ?></label>
			<select class="form-select form-select-sm" style="width: 100px;">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
			</select>
			<label><?php echo \Joomla\CMS\Language\Text::_("Color"); ?></label>
			<select class="form-select form-select-sm" style="width: 100px;">
				<option value="red" style="color: red"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCOLORRED"); ?></option>
				<option value="orange" style="color: orange"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCOLORORANGE"); ?></option>
				<option value="yellow" style="color: yellow"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCOLORYELLOW"); ?></option>
				<option value="green" style="color: green"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCOLORGREEN"); ?></option>
				<option value="blue" style="color: blue"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCOLORBLUE"); ?></option>
				<option value="violet" style="color: violet"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCOLORVIOLET"); ?></option>
				<option value="black" style="color: black"><?php echo \Joomla\CMS\Language\Text::_("TA_BBCOLORBLACK"); ?></option>
			</select>
		</div>
	</div>
	<div class="clearfix"></div>
<?php endif; ?>

<?php
$style = 'box-sizing: border-box;';
if($params->get('params.grow_enable', 0)) :
	$style .= 'max-height:' . $params->get('params.grow_max_height', 350) . 'px;';
	$style .= 'height:' . $params->get('params.grow_min_height', 50) . 'px;';
else:
	$style .= 'height:' . $params->get('params.height', '50px') . ';';
endif;
?>
	<div>
		<textarea name="jform[fields][<?php echo $this->id; ?>]" <?php echo $js . $class . $required; ?>
				  id="field_<?php echo $this->id; ?>"
				  style="<?php echo $style; ?>"
				  placeholder="<?php echo $params->get('params.placeholder', '') ?>"><?php echo $this->value; ?></textarea>
	</div>
	<div class="clearfix"></div>

<?php if($params->get('params.grow_enable', 0)) :
	\Joomla\CMS\Factory::getDocument()->addScript(\Joomla\CMS\Uri\Uri::root(TRUE) . '/components/com_joomcck/fields/textarea/assets/grow.js');
	?>
	<script type="text/javascript">
		jQuery("#field_<?php echo $this->id;?>").expanding();
	</script>
<?php endif; ?>

<?php if($params->get('params.bbcode', 0) && $params->get('params.bbcode_menu', 0)) :
	$id = $this->id;
	?>
	<script type="text/javascript">
		var bbEditor<?php echo $id;?> = new MintBBEditor();
		bbEditor<?php echo $id;?>.init('field_<?php echo $this->id;?>');

		var controls = jQuery('#controls_<?php echo $this->id;?> button');
		jQuery(controls[0]).on('click', function() {
			bbEditor<?php echo $id;?>.doAddTags('[b]', '[/b]');
		});
        jQuery(controls[1]).on('click', function() {
			bbEditor<?php echo $id;?>.doAddTags('[i]', '[/i]');
		});
        jQuery(controls[2]).on('click', function() {
			bbEditor<?php echo $id;?>.doAddTags('[u]', '[/u]');
		});
        jQuery(controls[3]).on('click', function() {
			bbEditor<?php echo $id;?>.doURL();
		});
        jQuery(controls[4]).on('click', function() {
			bbEditor<?php echo $id;?>.doImage();
		});
        jQuery(controls[5]).on('click', function() {
			bbEditor<?php echo $id;?>.doAddTags('[code]', '[/code]');
		});

		var selects = jQuery('#controls_<?php echo $this->id;?> select');

		jQuery(selects[0]).on('change', function() {
			bbEditor<?php echo $id;?>.doFontSize(this.value);
		});

		jQuery(selects[1]).on('change', function() {
			bbEditor<?php echo $id;?>.doColor(this.value);
		});
	</script>
<?php endif; ?>
<?php if($this->params->get('params.mention')): ?>
	<?php
	\Joomla\CMS\Factory::getDocument()->addStyleSheet(\Joomla\CMS\Uri\Uri::root(TRUE) . '/media/com_joomcck/vendors/At.js/dist/css/jquery.atwho.min.css');
	\Joomla\CMS\Factory::getDocument()->addScript(\Joomla\CMS\Uri\Uri::root(TRUE) . '/media/com_joomcck/vendors/At.js/dist/js/jquery.atwho.min.js');
	\Joomla\CMS\Factory::getDocument()->addScript(\Joomla\CMS\Uri\Uri::root(TRUE) . '/media/com_joomcck/vendors/Caret.js/dist/jquery.caret.min.js');
	?>
	<script type="text/javascript">
		(function($) {
			$("#field_<?php echo $this->id;?>").atwho({
				at: '@',
				limit: 10,
				data: '<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_joomcck&task=ajax.usermention', FALSE);  ?>',
				dispayTpl: "<li>${name} <small>${username}</small></li>",
				insertTpl: ':${name}:'
			});
		}(jQuery));
	</script>

<?php endif; ?>

<?php
$m = array();
if($this->params->get('params.mention'))
{
	$m[] = \Joomla\CMS\Language\Text::_('TA_MENTIONS');
}
if($params->get('params.markdown') && $this->params->get('params.markdown_text_show'))
{
	$m[] = \Joomla\CMS\Language\Text::_($this->params->get('params.markdown_text', 'TA_USEMARKDOWN'));
}
if($params->get('params.bbcode') && $this->params->get('params.bbcode_text_show'))
{
	$m[] = \Joomla\CMS\Language\Text::_($this->params->get('params.bbcode_text', 'TA_USEBBCODE'));
}

if($params->get('params.allow_html', 1) == 1)
{
	$m[] = \Joomla\CMS\Language\Text::_('TA_TAGSALLOWED');
}
if($params->get('params.allow_html', 1) == 2)
{
	$m[] = \Joomla\CMS\Language\Text::_('TA_SOMETAGSALLOWED');

	$tags = explode(',', $params->get('params.filter_tags'));
	ArrayHelper::trim_r($tags);
	ArrayHelper::clean_r($tags);
	$li[] = $this->params->get('params.tags_mode', 0) == 0 ? \Joomla\CMS\Language\Text::sprintf('TA_FOLLOWINGTAGSALLOWED') . ': ' . htmlspecialchars('<' . implode('>, <', $tags) . '>') : \Joomla\CMS\Language\Text::sprintf('TA_FOLLOWINGTAGSNOTALLOWED') . ': ' . htmlspecialchars('<' . implode('>, <', $tags) . '>');

	$attr = explode(',', $params->get('params.filter_attr',''));
	ArrayHelper::trim_r($attr);
	ArrayHelper::clean_r($attr);
	if($attr)
		$li[] = $this->params->get('params.attr_mode', 0) == 0 ? \Joomla\CMS\Language\Text::sprintf('TA_FOLLOWINGATTRSALLOWED') . ': ' . htmlspecialchars(implode('="", ', $attr)) . '=""' : \Joomla\CMS\Language\Text::sprintf('TA_FOLLOWINGATTRSNOTALLOWED') . ': ' . htmlspecialchars(implode('="", ', $attr)) . '=""';

	$m[] = '<ul><li>' . implode('</li><li>', $li) . '</li></ul>';
}
?>

<?php if(count($m)) : ?>
	<small><?php echo implode(", ", $m); ?></small>
<?php endif; ?>