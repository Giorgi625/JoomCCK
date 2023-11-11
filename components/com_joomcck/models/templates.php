<?php
/**
 * Joomcck by joomcoder
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: https://www.joomcoder.com/
 * @copyright Copyright (C) 2012 joomcoder (https://www.joomcoder.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

use Joomla\CMS\Factory;

defined('_JEXEC') or die();
jimport('mint.mvc.model.admin');

class JoomcckModelTemplates extends MModelAdmin
{

	var $_data = null;

	var $_fullData = null;

	var $_total = null;

	var $_pagination = null;

	var $_error = false;

	function getTmplObjectList($type)
	{
		$result = array();

		$layouts_path = JoomcckTmplHelper::getTmplPath($type);
		$tmpl_mask = JoomcckTmplHelper::getTmplMask($type);
		$files = \Joomla\CMS\Filesystem\Folder::files($layouts_path, $tmpl_mask['index_file']);
		foreach($files as $key => $file)
		{
			$result[$key] = new stdClass();
			$result[$key]->ident = preg_replace($tmpl_mask['ident'], '', $file);
			$result[$key]->type = $type;
			$xmlfile = JoomcckTmplHelper::getTmplFile($type, $result[$key]->ident . '.xml');
			$tmplXMLdata = $this->parseXMLTemplateFile($xmlfile);
			if($tmplXMLdata)
			{
				foreach($tmplXMLdata as $xmlkey => $value)
				{
					$result[$key]->$xmlkey = $value;
				}
			}
			$file_png = JoomcckTmplHelper::getTmplFile($type, $result[$key]->ident.'.png');
			$result[$key]->img_path = '';
			if(\Joomla\CMS\Filesystem\File::exists($file_png))
			{
				$img_path = JoomcckTmplHelper::getTmplImgSrc($type, $result[$key]->ident);
				$result[$key]->img_path = $img_path;
			}
		}
		return $result;
	}

function parseXMLTemplateFile($path)
	{
		// Read the file to see if it's a valid component XML file
		if(!\Joomla\CMS\Filesystem\File::exists($path))
		{
			\Joomla\CMS\Factory::getApplication()->enqueueMessage('File not found: '.$path,'warning');
			return false;
		}
		$xml = simplexml_load_file($path);

		$data = array();
		$element = & $xml->name;
		$data['name'] = $element ? $element : '';
		$element = & $xml->creationDate;
		$data['creationdate'] = $element ? $element : 'Unknown';
		$element = & $xml->author;
		$data['author'] = $element ? $element : 'Unknown';
		$element = & $xml->copyright;
		$data['copyright'] = $element ? $element : '';
		$element = & $xml->authorEmail;
		$data['authorEmail'] = $element ? $element : '';
		$element = & $xml->authorUrl;
		$data['authorUrl'] = $element ? $element : '';
		$element = & $xml->version;
		$data['version'] = $element ? $element : '';
		$element = & $xml->description;
		$data['description'] = $element ? $element : '';
		$element = & $xml->group;
		$data['group'] = $element ? $element : '';
		//var_dump( $xml->document );print'<br><br>';
		return $data;
	}
	function install()
	{
		$app = \Joomla\CMS\Factory::getApplication();

		$userfile = \Joomla\CMS\Factory::getApplication()->input->files->get('install_package', null, 'array');
		$tmp_dest 	= $app->getCfg('tmp_path'). DIRECTORY_SEPARATOR .$userfile['name'];
		$tmp_src	= $userfile['tmp_name'];

		if(!\Joomla\CMS\Filesystem\File::upload( $tmp_src, $tmp_dest ) ) {
			Factory::getApplication()->enqueueMessage(\Joomla\CMS\Language\Text::_('C_MASG_TMPLUPLOADFAIL'),'warning');
			return false;
		}

		$tmpdir = uniqid('install_');
		$extractdir = \Joomla\CMS\Filesystem\Path::clean(dirname($tmp_dest). DIRECTORY_SEPARATOR .$tmpdir);
		$archivename = \Joomla\CMS\Filesystem\Path::clean($tmp_dest);

		jimport('joomla.filesystem.archive');

		$result = JArchive::extract( $archivename, $extractdir);

		if ( $result === false ) {
			Factory::getApplication()->enqueueMessage(\Joomla\CMS\Language\Text::_('C_MASG_TMPLEXTRACTFAIL'),'warning');
			return false;
		}

		\Joomla\CMS\Filesystem\File::delete($archivename);

		$tmpl_types = JoomcckTmplHelper::getTmplTypes();

		foreach( $tmpl_types as $type ) {
			$mask = JoomcckTmplHelper::getTmplFullMask($type);

			$files = \Joomla\CMS\Filesystem\Folder::files( $extractdir, $mask['index_file'], true, true );

			if($files)
			{
				foreach( $files as $file ) {
					echo 'copy: '.$file.'-'.JoomcckTmplHelper::getTmplPath( $type ).strrchr($file, DIRECTORY_SEPARATOR).'<br />';
					\Joomla\CMS\Filesystem\File::copy( $file, JoomcckTmplHelper::getTmplPath( $type ).strrchr($file, DIRECTORY_SEPARATOR) );
				}
			}
			if( isset( $mask['folder'] ) ) {
				$folders = \Joomla\CMS\Filesystem\Folder::folders( $extractdir, $mask['folder'], true, true );
				if($folders)
				{
					foreach( $folders as $folder ) {
						\Joomla\CMS\Filesystem\Folder::copy( $folder, JoomcckTmplHelper::getTmplPath( $type ).strrchr($folder, DIRECTORY_SEPARATOR), '', true );
					}
				}
			}
		}

		\Joomla\CMS\Filesystem\Folder::delete($extractdir);

		return true;
	}

	function uninstall($tmpls)
	{
		foreach( $tmpls as $tmpl){
			preg_match("/^\[(.*)\]\,\[(.*)\]$/i", $tmpl, $matches );

			$layouts_path = JoomcckTmplHelper::getTmplPath( $matches[2] );

			$masks = JoomcckTmplHelper::getTmplFullMask( $matches[2], $matches[1] );

			if($masks['index_file'])
			{

				$files = \Joomla\CMS\Filesystem\Folder::files( $layouts_path, $masks['index_file'] );

				foreach( $files as $file ){
					\Joomla\CMS\Filesystem\File::delete( $layouts_path. DIRECTORY_SEPARATOR .$file );
				}
			}
			if($masks['folder'])
			{

				$folders = \Joomla\CMS\Filesystem\Folder::folders( $layouts_path, $masks['folder'], false, true );

				foreach( $folders as $folder ){
					\Joomla\CMS\Filesystem\Folder::delete( $folder );
				}
			}
		}
		return true;
	}

	public function change_name($file, $new_name)
	{
		$xml = new SimpleXMLElement(file_get_contents($file));
		$xml->name = $new_name;
		\Joomla\CMS\Filesystem\File::write($file, $xml->asXML());

		return true;
	}

	public function rename($tmpl, $new_name)
	{
		return $this->copy($tmpl, $new_name, 'move');

	}
	public function copy($tmpl, $new_name, $func = 'copy')
	{

		$new_name = strtolower($new_name);
		$new_name = preg_replace("/^[^a-z\-\_]$/", "", $new_name);

		$matches = array();

		preg_match ( "/^\[(.*)\]\,\[(.*)\]$/i", $tmpl, $matches );

		$layouts_path = JoomcckTmplHelper::getTmplPath( $matches[2] );

		switch( $matches[2] ){
		    case 'markup':
				$index_file_name    = "default_markup_%s.xml";
				$folder    			= "default_markup_".$new_name;
				$folder2    			= "default_markup_".$matches[1];
				$file_filter        = "^default_markup_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_markup).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'user_menu':
				$index_file_name    = "default_menu_%s.xml";
				$folder    			= "default_menu_".$new_name;
				$folder2    			= "default_menu_".$matches[1];
				$file_filter        = "^default_menu_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_menu).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'itemlist':
				$index_file_name    = "default_list_%s.xml";
				$folder    			= "default_list_".$new_name;
				$folder2    			= "default_list_".$matches[1];
				$file_filter        = "^default_list_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_list).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'filters':
				$index_file_name    = "default_filters_%s.xml";
				$folder    			= "default_filters_".$new_name;
				$folder2    			= "default_filters_".$matches[1];
				$file_filter        = "^default_filters_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_filters).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'category':
				$index_file_name    = "default_cindex_%s.xml";
				$folder    			= "default_cindex_".$new_name;
				$folder2    			= "default_cindex_".$matches[1];
				$file_filter        = "^default_cindex_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_cindex).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'comments':
				$index_file_name    = "default_comments_%s.xml";
				$folder    			= "default_comments_".$new_name;
				$folder2    			= "default_comments_".$matches[1];
				$file_filter        = "^default_comments_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_comments).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'article':
				$index_file_name    = "default_record_%s.xml";
				$folder    			= "default_record_".$new_name;
				$folder2    			= "default_record_".$matches[1];
				$file_filter        = "^default_record_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_record).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'articleform':
				$index_file_name    = "default_form_%s.xml";
				$folder    			= "default_form_".$new_name;
				$folder2    			= "default_form_".$matches[1];
				$file_filter        = "^default_form_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_form).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'categoryselect':
				$index_file_name    = "default_category_%s.xml";
				$folder    			= "default_category_".$new_name;
				$folder2    			= "default_category_".$matches[1];
				$file_filter        = "^default_category_".$matches[1]."\..{2,3}$";
				$file_new_tmpl_name = Array( "/^(default_category).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
			case 'rating':
				$index_file_name       = "rating_%s.php";
				$file_filter           = "^rating_".$matches[1]."\..{3}$";
				$folder_filter         = "^".$matches[1]."_img$";
				$file_new_ratig_name   = Array( "/^(rating).*(\..{3})$/", "\\1_%s\\2" );
				$folder_new_ratig_name = Array( "/^.*(_img)$/", "%s\\1" );
				$file_new_tmpl_name = Array( "/^(rating).*(\..{2,3})$/", "\\1_%s\\2" );
				break;
		}
		if($matches[1] == 'default' && $func == 'move' ){
			Factory::getApplication()->enqueueMessage(\Joomla\CMS\Language\Text::_('C_MSG_TMPLRENAMEFAIL'),'warning');
			return false;
		}
		if(!$new_name){
		$i = 0; $new_name = $matches[1]."_copy_{$i}";
			while(\Joomla\CMS\Filesystem\File::exists($layouts_path. DIRECTORY_SEPARATOR .sprintf($index_file_name, $new_name) ) ) {
				$oi = $i; $i++; $new_name = str_replace("_{$oi}", "_{$i}", $new_name);
			}
		} else if( \Joomla\CMS\Filesystem\File::exists($layouts_path. DIRECTORY_SEPARATOR .sprintf($index_file_name, $new_name)) ) {
			Factory::getApplication()->enqueueMessage(\Joomla\CMS\Language\Text::_('C_MSG_TMPLEXISTS'),'warning');
			return false;
		}

		$files = \Joomla\CMS\Filesystem\Folder::files( $layouts_path, $file_filter );

		foreach( $files as $file ){
			$original_file = $layouts_path. DIRECTORY_SEPARATOR .$file;
			$new_file      = $layouts_path. DIRECTORY_SEPARATOR .preg_replace ($file_new_tmpl_name[0], sprintf($file_new_tmpl_name[1], $new_name), $file );
			\Joomla\CMS\Filesystem\File::$func($original_file, $new_file);
		}
		$configs = JPATH_ROOT.'/components/com_joomcck/configs/';

		if(\Joomla\CMS\Filesystem\File::exists($configs.$folder2.'.json'))
		{
			\Joomla\CMS\Filesystem\File::$func($configs.$folder2.'.json', $configs.$folder.'.json');
		}

		if($matches[2] == 'rating')
		{
			$folders = \Joomla\CMS\Filesystem\Folder::folders( $layouts_path, $folder_filter );
			foreach( $folders as $folder ){
				\Joomla\CMS\Filesystem\Folder::$func( $layouts_path. DIRECTORY_SEPARATOR .$folder, $layouts_path. DIRECTORY_SEPARATOR .preg_replace ( $folder_new_ratig_name[0], sprintf($folder_new_ratig_name[1], $new_name), $folder ) );
			}
		}
		else
		{
			if(\Joomla\CMS\Filesystem\Folder::exists($layouts_path. DIRECTORY_SEPARATOR .str_replace($new_name, $matches[1], $folder)))
			{
				\Joomla\CMS\Filesystem\Folder::$func($layouts_path. DIRECTORY_SEPARATOR .str_replace($new_name, $matches[1], $folder), $layouts_path. DIRECTORY_SEPARATOR .$folder);
			}
		}

		return true;
	}

	public function getForm($data = array(), $loadData = TRUE)
	{
		if($this->_data)
		{
			return $this->_data;
		}
		$this->_data = new stdClass();
		$this->_data->markup = $this->getTmplObjectList('markup');
		$this->_data->itemlist = $this->getTmplObjectList('itemlist');
		$this->_data->rating = $this->getTmplObjectList('rating');
		$this->_data->comments = $this->getTmplObjectList('comments');
		$this->_data->article = $this->getTmplObjectList('article');
		$this->_data->articleform = $this->getTmplObjectList('articleform');
		$this->_data->categoryselect = $this->getTmplObjectList('categoryselect');
		$this->_data->filters = $this->getTmplObjectList('filters');
		$this->_data->category = $this->getTmplObjectList('category');
		$this->_data->menu = $this->getTmplObjectList('user_menu');
		return $this->_data;
	}
}
?>