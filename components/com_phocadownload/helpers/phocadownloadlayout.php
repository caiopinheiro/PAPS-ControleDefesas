<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */

class PhocaDownloadLayout
{	
	public $params;
	public $filePath;
	public $iconPath;
	public $cssImagePath;
	public $fileAbsPath;
	
	public function __construct() {
		if (empty($params)) {
			$this->params = JComponentHelper::getParams('com_phocadownload') ;
		}
		
		if ($this->filePath == '') {
			$this->filePath		= PhocaDownloadHelper::getPathSet('file');
		}
		
		if ($this->iconPath == '') {
			$this->iconPath		= PhocaDownloadHelper::getPathSet('icon');
		}
		
		if ($this->cssImagePath == '') {
			$this->cssImagePath	= str_replace ( '../', JURI::base(true).'/', $this->iconPath['orig_rel_ds']);
		}
		
		if ($this->fileAbsPath == '') {
			$this->fileAbsPath	= $this->filePath['orig_abs_ds'];
		}
	}
	
	
	public function getName($title, $filename, $preferTitle = 0) {
		
		$name	= $title;
		$fon	= $this->params->get( 'filename_or_name', 'filename' );

		if ($fon == 'title') {
			$name = $title;
		} else if ($fon == 'filename'){
			$name = PhocaDownloadHelper::getTitleFromFilenameWithExt( $filename );
		} else if ($fon == 'filenametitle'){
			if ($preferTitle == 1) {
				$name = $title;
			} else {
				// Must be solved before
				$name = PhocaDownloadHelper::getTitleFromFilenameWithExt( $filename );
			}
		}
		
		return $name;
	}
	
	public function getImageFileName($imageFilename) {
		
		$name['filenamestyle'] 	= '';
		$name['filenamethumb']	= '';

		if ($imageFilename !='') {
			$thumbnail = false;
			$thumbnail = preg_match("/phocathumbnail/i", $imageFilename);
			if ($thumbnail) {
				$name['filenamethumb']	= '<div class="pdfv-image-file-thumb" >'
				.'<img src="'.$this->cssImagePath.$imageFilename.'" alt="" /></div>';
				$name['filenamestyle']	= '';
			} else {
				$name['filenamethumb']	= '';
				$name['filenamestyle'] 	= 'style="background: url(\''.$this->cssImagePath.$imageFilename.'\') 0 center no-repeat;"';
			}
		}
		
		return $name;
	}
	
	public function getFileSize($filename) {
		
		$size = '';
		if ($filename != '') {
			$absFile = str_replace('/', DS, JPath::clean($this->fileAbsPath . $filename));
			if (JFile::exists($absFile)) {
				$size = PhocaDownloadHelper::getFileSizeReadable(filesize($absFile));
			} else {
				$size = '';
			}
		}
		
		return $size;
	}
	
	public function getProtectEmail($email) {
	
		$email = str_replace('@', '['.JText::_('COM_PHOCADOWNLOAD_AT').']', $email);
		$email = str_replace('.', '['.JText::_('COM_PHOCADOWNLOAD_DOT').']', $email);
		
		return $email;
	}
	
	public function getFileDate($filename, $date) {
	
		$dateO 	= '';
		$ddt	= $this->params->get( 'display_date_type', 0 );
		if ((int)$ddt > 0) {
			if ($filename !='') {
				$dateO = PhocaDownloadHelper::getFileTime($filename, $ddt);
			}
		} else {
			$dateO = JHTML::Date($date, JText::_('DATE_FORMAT_LC3'));
		}
	
		return $dateO;
	}
	
	public function isValueEditor($text) {
	
		if ($text != '' && $text != '<p>&#160;</p>' && $text != '<p>&nbsp;</p>' && $text != '<p></p>' && $text != '<br />') {
			return true;
		}
		return false;
	}
	
	public function getImageDownload($img) {
	
		return '<img src="'.$this->cssImagePath . $img.'" alt="" />';
	}
	
	public function displayTags($fileId) {
	
		$o = '';
		$db =& JFactory::getDBO();
				
		$query = 'SELECT a.id, a.title, a.link_ext, a.link_cat'
		.' FROM #__phocadownload_tags AS a'
		.' LEFT JOIN #__phocadownload_tags_ref AS r ON r.tagid = a.id'
		.' WHERE r.fileid = '.(int)$fileId;

		$db->setQuery($query);
		$fileIdObject = $db->loadObjectList();
		
		if (!$db->query()) {
			$this->setError($db->getErrorMsg());
			return false;
		}
		
		$tl	= $this->params->get( 'tags_links', 0 );

		foreach ($fileIdObject as $k => $v) {
			$o .= '<span>';
			if ($tl == 0) {
				$o .= $v->title;
			} else if ($tl == 1) {
				if ($v->link_ext != '') {
					$o .= '<a href="'.$v->link_ext.'">'.$v->title.'</a>';
				} else {
					$o .= $v->title;
				}
			} else if ($tl == 2) {
				
				if ($v->link_cat != '') {
					$query = 'SELECT a.id, a.alias'
					.' FROM #__phocadownload_categories AS a'
					.' WHERE a.id = '.(int)$v->link_cat;

					$db->setQuery($query, 0, 1);
					$category = $db->loadObject();
					
					if (!$db->query()) {
						$this->setError($db->getErrorMsg());
						return false;
					}
					if (isset($category->id) && isset($category->alias)) {
						$link = PhocaDownloadHelperRoute::getCategoryRoute($category->id, $category->alias);
						$o .= '<a href="'.$link.'">'.$v->title.'</a>';
					} else {
						$o .= $v->title;
					}
				} else {
					$o .= $v->title;
				}
			} else if ($tl == 3) {
				$link = PhocaDownloadHelperRoute::getCategoryRouteByTag($v->id);
				$o .= '<a href="'.$link.'">'.$v->title.'</a>';
			}
			
			$o .= '</span> ';
		}

		return $o;
	}
	
	public function displayVideo($url, $view = 0) {
	
		$o = '';
		if ($url != '' && PhocaDownloadHelper::isURLAddress($url) ) {
			
			$shortUrl	= 'http://youtu.be/';
			$pos 		= strpos($url, $shortUrl);
			if ($pos !== false) {
				$code 		= str_replace($shortUrl, '', $url);
			} else {
				$codeArray 	= explode('=', $url);
				$code 		= str_replace($codeArray[0].'=', '', $url);
			}
			
			if ($view == 0) {
				// Category View
				$youtubeheight	= $this->params->get( 'youtube_height_cv', 240 );
				$youtubewidth	= $this->params->get( 'youtube_width_cv', 320 );
			} else {
				// Detail View
				$youtubeheight	= $this->params->get( 'youtube_height_dv', 360 );
				$youtubewidth	= $this->params->get( 'youtube_width_dv', 480 );
			}

			$o .= '<object height="'.(int)$youtubeheight.'" width="'.(int)$youtubewidth.'">'
			.'<param name="movie" value="http://www.youtube.com/v/'.$code.'"></param>'
			.'<param name="allowFullScreen" value="true"></param>'
			.'<param name="allowscriptaccess" value="always"></param>'
			.'<embed src="http://www.youtube.com/v/'.$code.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="'.(int)$youtubeheight.'" width="'.(int)$youtubewidth.'"></embed></object>';
		}
		return $o;
	}
}
?>