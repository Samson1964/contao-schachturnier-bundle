<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package News
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_schachturnier
 */
$GLOBALS['TL_DCA']['tl_schachturnier'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_schachturnier_spieler', 'tl_schachturnier_partien', 'tl_schachturnier_termine'),
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id'    => 'primary'
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('toDate DESC'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,filter;search,limit',
		),
		'label' => array
		(
			'fields'                  => array('title', 'fromDate', 'toDate', 'complete'),
			'showColumns'             => true,
			'format'                  => '%s %s %s %s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'editTermine' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['editTermine'],
				'href'                => 'table=tl_schachturnier_termine',
				'icon'                => 'bundles/contaoschachturnier/images/termin.png',
			),
			'editSpieler' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['editSpieler'],
				'href'                => 'table=tl_schachturnier_spieler',
				'icon'                => 'bundles/contaoschachturnier/images/players.png',
			),
			'editPartien' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['editPartien'],
				'href'                => 'table=tl_schachturnier_partien',
				'icon'                => 'bundles/contaoschachturnier/images/games.png',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif',
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_schachturnier', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{title_legend},title,type;{date_legend},fromDate,toDate;{wertungen_legend},wertungen;{aufabstieg_legend:hide},aufsteiger,absteiger;{publish_legend},published,complete'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'title' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['title'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'mandatory'           => true, 
				'maxlength'           => 255,
				'tl_class'            => 'w50'
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['type'],
			'exclude'                 => true,
			'filter'                  => true,
			'default'                 => '',
			'inputType'               => 'select',
			'options'                 => $GLOBALS['TL_LANG']['tl_schachturnier']['typen'], 
			'eval'                    => array
			(
				'includeBlankOption'  => true,
				'mandatory'           => false,
				'doNotCopy'           => false,
				'tl_class'            => 'w50',
			),
			'sql'                     => "char(2) NOT NULL default ''"
		),  
		'fromDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['fromDate'],
			'default'                 => date('d.m.Y'),
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 6,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'rgxp'                => 'date',
				'mandatory'           => false,
				'doNotCopy'           => false,
				'datepicker'          => true,
				'maxlength'           => 10,
				'tl_class'            => 'w50 wizard'
			),
			'load_callback'           => array
			(
				array('tl_schachturnier', 'loadDate')
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		), 
		'toDate' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['toDate'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 6,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'rgxp'                => 'date',
				'mandatory'           => false,
				'doNotCopy'           => false,
				'datepicker'          => true,
				'maxlength'           => 10,
				'tl_class'            => 'w50 wizard'
			),
			'load_callback'           => array
			(
				array('tl_schachturnier', 'loadDate')
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),  
		'wertungen' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['wertungen'],
			'exclude'                 => true,
			'options'                 => &$GLOBALS['TL_LANG']['tl_schachturnier']['wertungen_options'],
			'inputType'               => 'checkboxWizard',
			'eval'                    => array
			(
				'multiple'            => true,
				'tl_class'            => 'long',
			),
			'sql'                     => "blob NULL"
		),  
		'aufsteiger' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['aufsteiger'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'rgxp'                => 'natural',
				'mandatory'           => false,
				'doNotCopy'           => false,
				'maxlength'           => 3,
				'tl_class'            => 'w50'
			),
			'sql'                     => "int(3) unsigned NOT NULL default '0'"
		),  
		'absteiger' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['absteiger'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'rgxp'                => 'natural',
				'mandatory'           => false,
				'doNotCopy'           => false,
				'maxlength'           => 3,
				'tl_class'            => 'w50'
			),
			'sql'                     => "int(3) unsigned NOT NULL default '0'"
		),  
		'complete' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['complete'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'tl_class'            => 'w50 m12',
			),
			'sql'                     => "char(1) NOT NULL default ''"
		),  
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'default'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'tl_class'            => 'w50 m12',
			),
			'sql'                     => "char(1) NOT NULL default ''"
		),  
	)
);


/**
 * Class tl_schachturnier
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class tl_schachturnier extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Set the timestamp to 00:00:00 (see #26)
	 *
	 * @param integer $value
	 *
	 * @return integer
	 */
	public function loadDate($value)
	{
		return strtotime(date('Y-m-d', $value) . ' 00:00:00');
	}

	/**
	 * Ändert das Aussehen des Toggle-Buttons.
	 * @param $row
	 * @param $href
	 * @param $label
	 * @param $title
	 * @param $icon
	 * @param $attributes
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		$this->import('BackendUser', 'User');
		
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 0));
			$this->redirect($this->getReferer());
		}
		
		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_schachturnier::published', 'alexf'))
		{
			return '';
		}
		
		$href .= '&amp;id='.$this->Input->get('id').'&amp;tid='.$row['id'].'&amp;state='.$row[''];
		
		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}
		
		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}

	/**
	 * Toggle the visibility of an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnPublished)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_schachturnier::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_schachturnier toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		$this->createInitialVersion('tl_schachturnier', $intId);
		
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_schachturnier']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_schachturnier']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
		
		// Update the database
		$this->Database->prepare("UPDATE tl_schachturnier SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
		     ->execute($intId);
		$this->createNewVersion('tl_schachturnier', $intId);
	}

}
