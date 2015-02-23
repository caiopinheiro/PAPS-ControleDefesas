<?php
defined('_JEXEC') or die('Restricted access'); 

$l = new PhocaDownloadLayout();
// Files

if (!empty($this->files)) {	
	foreach ($this->files as $v) {
	
		if ($this->checkRights == 1) {
			// USER RIGHT - Access of categories (if file is included in some not accessed category) - - - - -
			// ACCESS is handled in SQL query, ACCESS USER ID is handled here (specific users)
			$rightDisplay	= 0;
			if (isset($v->catid) && isset($v->cataccessuserid) && isset($v->cataccess)) {
				$rightDisplay = PhocaDownloadHelper::getUserRight('accessuserid', $v->cataccessuserid, $v->cataccess, $this->tmpl['user']->authorisedLevels(), $this->tmpl['user']->get('id', 0), 0);
			}
			// - - - - - - - - - - - - - - - - - - - - - -
		} else {
			$rightDisplay = 1;
		}
		
		if ($rightDisplay == 1) {
		
			// General
			$linkDownloadB = '';
			$linkDownloadE = '';
			if ((int)$v->confirm_license > 0 || $this->tmpl['display_file_view'] == 1) {
				$linkDownloadB = '<a href="'. JRoute::_(PhocaDownloadHelperRoute::getFileRoute($v->id, $v->catid,$v->alias, $v->categoryalias, $v->sectionid). $this->tmpl['limitstarturl']).'" >';	// we need pagination to go back			
				$linkDownloadE ='</a>';
			} else {
				if ($v->link_external != '' && $v->directlink == 1) {
					$linkDownloadB = '<a href="'.$v->link_external.'" target="'.$this->tmpl['download_external_link'].'" >';
					$linkDownloadE ='</a>';
				} else {
					$linkDownloadB = '<a href="'. JRoute::_(PhocaDownloadHelperRoute::getFileRoute($v->id,$this->category[0]->id,$v->alias, $this->category[0]->alias, $v->sectionid, 'download').$this->tmpl['limitstarturl']).'" >';
					$linkDownloadE ='</a>';
				}
			}
			
			// pdtextonly
			$pdTextOnly = '<div class="pd-textonly">'.$v->description.'</div>' . "\n";
			
			// pdfile
			if ($v->filename != '') {
				$imageFileName = $l->getImageFileName($v->image_filename);
				
				$pdFile = '<div class="pd-filenamebox">';
				if ($this->tmpl['filename_or_name'] == 'filenametitle') {
					$pdFile .= '<div class="pd-title">'. $v->title . '</div>';
				}
				
				$pdFile .= '<div class="pd-filename">'. $imageFileName['filenamethumb']
					. '<div class="pd-document'.$this->tmpl['file_icon_size'].'" '
					. $imageFileName['filenamestyle'].'>';
				
				$pdFile .= '<div class="pd-float">';
				$pdFile .= $linkDownloadB .$l->getName($v->title, $v->filename) .$linkDownloadE;
				$pdFile .= '</div>';
				
				$pdFile .= PhocaDownloadHelper::displayNewIcon($v->date, $this->tmpl['displaynew']);
				$pdFile .= PhocaDownloadHelper::displayHotIcon($v->hits, $this->tmpl['displayhot']);
				
				//Specific icons
				if (isset($v->image_filename_spec1) && $v->image_filename_spec1 != '') {
					$pdFile .= '<div class="pd-float">'.$l->getImageDownload($v->image_filename_spec1).'</div>';
				} 
				if (isset($v->image_filename_spec2) && $v->image_filename_spec2 != '') {
					$pdFile .= '<div class="pd-float">'.$l->getImageDownload($v->image_filename_spec2).'</div>';
				} 
				
				$pdFile .= '</div></div></div>' . "\n";
			}
			
			// pdbuttonplay
			$pdButtonPlay = '';
			if (isset($v->filename_play) && $v->filename_play != '') {
				$fileExt 	= PhocaDownloadHelper::getExtension($v->filename_play);
				$canPlay	= PhocaDownloadHelper::canPlay($v->filename_play);
				
				if ($canPlay) {
					// Special height for music only
					$buttonPlOptions = $this->buttonpl->options;
					if ($fileExt == 'mp3') {
						$buttonPlOptions = $this->buttonpl->optionsmp3;
					}
					$playLink = JRoute::_(PhocaDownloadHelperRoute::getFileRoute($v->id,$v->catid,$v->alias, $v->categoryalias,0, 'play').$this->tmpl['limitstarturl']);
					$pdButtonPlay .= '<div class="pd-button-play">';
					if ($this->tmpl['play_popup_window'] == 1) {
						$pdButtonPlay .= '<a  href="'.$playLink.'" onclick="'. $buttonPlOptions.'" >'. JText::_('COM_PHOCADOWNLOAD_PLAY').'</a>';
					} else {	
						$pdButtonPlay .= '<a class="pd-modal-button" href="'.$playLink.'" rel="'. $buttonPlOptions.'" >'. JText::_('COM_PHOCADOWNLOAD_PLAY').'</a>';
					}
					$pdButtonPlay .= '</div>';
				}
			}
			
			// pdbuttonpreview
			$pdButtonPreview = '';
			if (isset($v->filename_preview) && $v->filename_preview != '') {
				$fileExt = PhocaDownloadHelper::getExtension($v->filename_preview);
				if ($fileExt == 'pdf' || $fileExt == 'jpeg' || $fileExt == 'jpg' || $fileExt == 'png' || $fileExt == 'gif') {
		
					$filePath	= PhocaDownloadHelper::getPathSet('file');
					$filePath	= str_replace ( '../', JURI::base(true).'/', $filePath['orig_rel_ds']);
					$previewLink = $filePath . $v->filename_preview;	
					$pdButtonPreview	.= '<div class="pd-button-preview">';
					
					if ($this->tmpl['preview_popup_window'] == 1) {
						$pdButtonPreview .= '<a  href="'.$previewLink.'" onclick="'. $this->buttonpr->options.'" >'. JText::_('COM_PHOCADOWNLOAD_PREVIEW').'</a>';
					} else {	
						if ($fileExt == 'pdf') {
							// Iframe - modal
							$pdButtonPreview .= '<a class="pd-modal-button" href="'.$previewLink.'" rel="'. $this->buttonpr->options.'" >'. JText::_('COM_PHOCADOWNLOAD_PREVIEW').'</a>';
						} else {
							// Image - modal
							$pdButtonPreview .= '<a class="pd-modal-button" href="'.$previewLink.'" rel="'. $this->buttonpr->optionsimg.'" >'. JText::_('COM_PHOCADOWNLOAD_PREVIEW').'</a>';
						}
					}
					$pdButtonPreview	.= '</div>';
				}
			}
			
			// pdbuttondownload
			$pdButtonDownload = '<div class="pd-button-download">';
			$pdButtonDownload .= $linkDownloadB . JText::_('COM_PHOCADOWNLOAD_DOWNLOAD') .$linkDownloadE;
			$pdButtonDownload .= '</div>';
			
			
			
			// pdbuttondetails
			$d = '';
			
			$pdTitle = '';
			if ($v->title != '') {
				$pdTitle .= '<div class="pd-title">'.$v->title.'</div>';
				$d .= $pdTitle;
			}
			
			$pdImage = '';
			if ($v->image_download != '') {
				$pdImage .= '<div class="pd-image">'.$l->getImageDownload($v->image_download).'</div>';
				$d .= $pdImage;			
			}
			
			$pdFileSize = '';
			$fileSize = $l->getFilesize($v->filename);
			if ($fileSize != '') {
				$pdFileSize .= '<div class="pd-filesize-txt">'.JText::_('COM_PHOCADOWNLOAD_FILESIZE').':</div>';
				$pdFileSize .= '<div class="pd-fl-m">'.$fileSize.'</div>';
				$d .= $pdFileSize;
			}
				
			$pdVersion = '';
			if ($v->version != '') {
				$pdVersion .= '<div class="pd-version-txt">'.JText::_('COM_PHOCADOWNLOAD_VERSION').':</div>';
				$pdVersion .= '<div class="pd-fl-m">'.$v->version.'</div>';
				$d .= $pdVersion;
			}
			
			$pdLicense = '';
			if ($v->license != '') {
				if ($v->license_url != '') {
					$pdLicense .= '<div class="pd-license-txt">'.JText::_('COM_PHOCADOWNLOAD_LICENSE').':</div>';
					$pdLicense .= '<div class="pd-fl-m"><a href="'.$v->license_url.'" target="_blank">'.$v->license.'</a></div>';
				} else {
					$pdLicense .= '<div class="pd-license-txt">'.JText::_('COM_PHOCADOWNLOAD_LICENSE').':</div>';
					$pdLicense .= '<div class="pd-fl-m">'.$v->license.'</div>';
				}
				$d .= $pdLicense;
			}
			
			$pdAuthor = '';
			if ($v->author != '') {
				if ($v->author_url != '') {
					$pdAuthor .= '<div class="pd-author-txt">'.JText::_('COM_PHOCADOWNLOAD_AUTHOR').':</div>';
					$pdAuthor .= '<div class="pd-fl-m"><a href="'.$v->author_url.'" target="_blank">'.$v->author.'</a></div>';
				} else {
					$pdAuthor .= '<div class="pd-author-txt">'.JText::_('COM_PHOCADOWNLOAD_AUTHOR').':</div>';
					$pdAuthor .= '<div class="pd-fl-m">'.$v->author.'</div>';
				}
				$d .= $pdAuthor;
			}
			
			$pdAuthorEmail = '';
			if ($v->author_email != '') {
				$pdAuthorEmail .= '<div class="pd-email-txt">'.JText::_('COM_PHOCADOWNLOAD_EMAIL').':</div>';
				$pdAuthorEmail .= '<div class="pd-fl-m">'. $l->getProtectEmail($v->author_email).'</div>';
				$d .= $pdAuthorEmail;
			}
			
			$pdFileDate = '';
			$fileDate = $l->getFileDate($v->filename, $v->date);
			if ($fileDate != '') {
				$pdFileDate .= '<div class="pd-date-txt">'.JText::_('COM_PHOCADOWNLOAD_DATE').':</div>';
				$pdFileDate .= '<div class="pd-fl-m">'.$fileDate.'</div>';
				$d .= $pdFileDate;
			}
				
			$pdDownloads = '';
			if ($this->tmpl['display_downloads'] == 1) {
				$pdDownloads .= '<div class="pd-downloads-txt">'.JText::_('COM_PHOCADOWNLOAD_DOWNLOADS').':</div>';
				$pdDownloads .= '<div class="pd-fl-m">'.$v->hits.' x</div>';
				$d .= $pdDownloads;
			}
			
			$pdDescription = '';
			if ($l->isValueEditor($v->description) && $this->tmpl['display_description'] != 1 && $this->tmpl['display_description'] != 2 && $this->tmpl['display_description'] != 3) {
				$pdDescription .= '<div class="pd-fdesc">'.$v->description.'</div>';
				$d .= $pdDescription;
			}
			
			$pdFeatures = '';
			if ($l->isValueEditor($v->features)) {
				$pdFeatures .= '<div class="pd-features-txt">'.JText::_('COM_PHOCADOWNLOAD_FEATURES').'</div>';
				$pdFeatures .= '<div class="pd-features">'.$v->features.'</div>';
			}
			
			$pdChangelog = '';
			if ($l->isValueEditor($v->changelog)) {
				$pdChangelog .= '<div class="pd-changelog-txt">'.JText::_('COM_PHOCADOWNLOAD_CHANGELOG').'</div>';
				$pdChangelog .= '<div class="pd-changelog">'.$v->changelog.'</div>';
			}
			
			$pdNotes = '';
			if ($l->isValueEditor($v->notes)) {
				$pdNotes .= '<div class="pd-notes-txt">'.JText::_('COM_PHOCADOWNLOAD_NOTES').'</div>';
				$pdNotes .= '<div class="pd-notes">'.$v->notes.'</div>';
			}

			
			// pdfiledesc
			$description = $l->isValueEditor($v->description);
			
			$pdFileDescTop 		= '';
			$pdFileDescBottom	= '';
			$oFileDesc			= '';
			
			if ($description) {
				switch($this->tmpl['display_description']) {
					
					case 1:
						$pdFileDescTop		= '<div class="pd-fdesc">'.$v->description.'</div>';
					break;
					case 2:
						$pdFileDescBottom	= '<div class="pd-fdesc">'.$v->description.'</div>';
					break;
					case 3:
						$oFileDesc			= '<div class="pd-fdesc">'.$v->description.'</div>';
					break;
					case 4:
						$pdFileDescTop		= '<div class="pd-fdesc">'.$v->description.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadHelper::strTrimAll($d).'</div>';
					break;
					case 5:
						$pdFileDescBottom	= '<div class="pd-fdesc">'.$v->description.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadHelper::strTrimAll($d).'</div>';
					break;
					case 6:
						$pdFileDescTop		= '<div class="pd-fdesc">'.$d.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadHelper::strTrimAll($d).'</div>';
					break;
					case 7:
						$pdFileDescBottom	= '<div class="pd-fdesc">'.$d.'</div>';
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadHelper::strTrimAll($d).'</div>';
					break;
					
					case 8:
						$oFileDesc			= '<div class="pd-fdesc">'.PhocaDownloadHelper::strTrimAll($d).'</div>';
					break;
					
					default:
					break;
				}
			}
			
			// Detail Button
			if ($this->tmpl['display_detail'] == 1) {
				if ($oFileDesc	!= '') {
					$overlibcontent = $oFileDesc;
				} else {
					$overlibcontent = $d;
				}
				
				$overlibcontent = str_replace('"', '\'', $overlibcontent);
				$textO = htmlspecialchars(addslashes('<div style=\'text-align:left;padding:5px\'>'.$overlibcontent.'</div>'));
				$overlib 	= "onmouseover=\"return overlib('".$textO."', CAPTION, '".JText::_('COM_PHOCADOWNLOAD_DETAILS')."', BELOW, RIGHT, CSSCLASS, TEXTFONTCLASS, 'fontPhocaPDClass', FGCLASS, 'fgPhocaPDClass', BGCLASS, 'bgPhocaPDClass', CAPTIONFONTCLASS,'capfontPhocaPDClass', CLOSEFONTCLASS, 'capfontclosePhocaPDClass', STICKY, MOUSEOFF, CLOSETEXT, '".JText::_('COM_PHOCADOWNLOAD_CLOSE')."');\"";
				$overlib .= " onmouseout=\"return nd();\"";
			
				$pdButtonDetails = '<div class="pd-button-details">';
				$pdButtonDetails .= '<a '.$overlib.' href="#">'. JText::_('COM_PHOCADOWNLOAD_DETAILS').'</a>';
				$pdButtonDetails .= '</div>';
			} else if ($this->tmpl['display_detail'] == 2) {
				$buttonDOptions = $this->buttond->options;
				$detailLink 	= JRoute::_(PhocaDownloadHelperRoute::getFileRoute($v->id,$this->category[0]->id,$v->alias, $v->categoryalias, 0, 'detail').$this->tmpl['limitstarturl']);
				$pdButtonDetails = '<div class="pd-button-details">';
				$pdButtonDetails .= '<a class="pd-modal-button" href="'.$detailLink.'" rel="'. $buttonDOptions.'">'. JText::_('COM_PHOCADOWNLOAD_DETAILS').'</a>';
				$pdButtonDetails .= '</div>';
			} else {
				$pdButtonDetails = '';
			}
			
			
			// pdmirrorlink1
			$pdMirrorLink1 = '';
			$mirrorOutput1 = PhocaDownloadHelper::displayMirrorLinks(1, $v->mirror1link, $v->mirror1title, $v->mirror1target);
			if ($mirrorOutput1 != '') {
				
				if ($this->tmpl['display_mirror_links'] == 4 || $this->tmpl['display_mirror_links'] == 6) {
					$classMirror = 'pd-button-mirror1';
				} else {
					$classMirror = 'pd-mirror';
				}
				
				$pdMirrorLink1 = '<div class="'.$classMirror.'">'.$mirrorOutput1.'</div>';
			}

			// pdmirrorlink2
			$pdMirrorLink2 = '';
			$mirrorOutput2 = PhocaDownloadHelper::displayMirrorLinks(1, $v->mirror2link, $v->mirror2title, $v->mirror2target);
			if ($mirrorOutput2 != '') {
				if ($this->tmpl['display_mirror_links'] == 4 || $this->tmpl['display_mirror_links'] == 6) {
					$classMirror = 'pd-button-mirror2';
				} else {
					$classMirror = 'pd-mirror';
				}
			
				$pdMirrorLink2 = '<div class="'.$classMirror.'">'.$mirrorOutput2.'</div>';
			}
			
			// pdreportlink
			$pdReportLink = PhocaDownloadHelper::displayReportLink(1, $v->title);

			
			// pdrating
			$pdRating 	= PhocaDownloadRateHelper::renderRateFile($v->id, $this->tmpl['display_rating_file']);
			
			// pdtags
			$pdTags = '';
			if ($this->tmpl['display_tags_links'] == 1 || $this->tmpl['display_tags_links'] == 3) {
				if ($l->displayTags($v->id) != '') {
					$pdTags .= $l->displayTags($v->id);
				}
			
			}
			
			//pdvideo
			$pdVideo = $l->displayVideo($v->video_filename, 0);
			
			
			// ---------------------------------------------------
			//Convert
			// ---------------------------------------------------
			if ($v->textonly == 1) {
				echo '<div class="pd-textonly">'. $pdTextOnly . '</div>';
			} else {

				if ($this->tmpl['display_specific_layout'] == 0) {
					echo '<div class="pd-filebox">';
					echo $pdFileDescTop;
					echo $pdFile;
					echo '<div class="pd-buttons">'.$pdButtonDownload.'</div>';
					
					if ($this->tmpl['display_detail'] == 1 || $this->tmpl['display_detail'] == 2) {
						echo '<div class="pd-buttons">'.$pdButtonDetails.'</div>';
					}
					
					if ($this->tmpl['display_preview'] == 1 && $pdButtonPreview != '') {
						echo '<div class="pd-buttons">'.$pdButtonPreview.'</div>';
					}
					
					if ($this->tmpl['display_play'] == 1 && $pdButtonPlay != '') {
						echo '<div class="pd-buttons">'.$pdButtonPlay.'</div>';
					}
					
					if ($this->tmpl['display_mirror_links'] == 4 || $this->tmpl['display_mirror_links'] == 6) {
						if ($pdMirrorLink2 != '') {
							echo '<div class="pd-buttons">'.$pdMirrorLink2.'</div>';
						}
						if ($pdMirrorLink1 != '') {
							echo '<div class="pd-buttons">'.$pdMirrorLink1.'</div>';
						}

					} else if ($this->tmpl['display_mirror_links'] == 1 || $this->tmpl['display_mirror_links'] == 3) {
						echo '<div class="pd-mirrors">'.$pdMirrorLink2.$pdMirrorLink1.'</div>';
					}
					
					if ($pdVideo != '') {
						echo '<div class="pd-video">'.$pdVideo.'</div>';
					}
					echo '<div class="pd-report">'.$pdReportLink.'</div>';
					echo '<div class="pd-rating">'.$pdRating.'</div>';
					echo '<div class="pd-tags">'.$pdTags.'</div>';
					echo $pdFileDescBottom;
					echo '<div class="pd-cb"></div>';
					echo '</div>';
				
				} else {
				
				/*$categoryLayout = '<div class="pd-filebox">
				{pdfiledesctop}
				{pdfile}
				<div class="pd-buttons">{pdbuttondownload}</div>
				<div class="pd-buttons">{pdbuttondetails}</div>
				<div class="pd-buttons">{pdbuttonpreview}</div>
				<div class="pd-buttons">{pdbuttonplay}</div>
				<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
				<div class="pd-rating">{pdrating}</div>
				<div class="pd-tags">{pdtags}</div>
				{pdfiledescbottom}
				<div class="pd-cb"></div>
				</div>';*/
				
					$categoryLayout 		= PhocaDownloadHelper::getLayoutText('category');
					$categoryLayoutParams 	= PhocaDownloadHelper::getLayoutParams('category');
						
					$replace	= array($pdTitle, $pdImage, $pdFile, $pdFileSize, $pdVersion, $pdLicense, $pdAuthor, $pdAuthorEmail, $pdFileDate, $pdDownloads, $pdDescription, $pdFeatures, $pdChangelog, $pdNotes, $pdMirrorLink1, $pdMirrorLink2, $pdReportLink, $pdRating, $pdTags, $pdFileDescTop, $pdFileDescBottom, $pdButtonDownload, $pdButtonDetails, $pdButtonPreview, $pdButtonPlay, $pdVideo );
					$output		= str_replace($categoryLayoutParams['search'], $replace, $categoryLayout);
					
					echo $output;
				}
			// ---------------------------------------------------	
			}
		}
	}
}
?>
