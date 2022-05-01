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
 * Table tl_schachturnier_termine
 */
$GLOBALS['TL_DCA']['tl_schachturnier_termine'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_schachturnier',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary',
				'pid' => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 4,
			'disableGrouping'         => true,
			'headerFields'            => array('title'),
			'fields'                  => array('runde ASC'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_schachturnier_termine', 'listTermine'),  
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
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{runde_legend},runde,datum;{publish_legend},published'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_schachspieler.id',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'runde' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['nummer'],
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'maxlength'           => 4,
				'rgxp'                => 'digit',
				'tl_class'            => 'w50'
			),
			'load_callback'           => array
			(
				array('tl_schachturnier_termine', 'getNummer')
			),
			'save_callback' => array
			(
				array('tl_schachturnier_termine', 'putNummer')
			),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'datum' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['datum'],
			'default'                 => date('d.m.Y'),
			'exclude'                 => true,
			'search'                  => true,
			'flag'                    => 5,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'mandatory'           => false, 
				'maxlength'           => 10,
				'datepicker'          => true,
				'tl_class'            => 'w50 wizard',
				'rgxp'                => 'date'
			),
			'load_callback'           => array
			(
				array('tl_schachturnier_termine', 'loadDate')
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		), 
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_termine']['published'],
			'default'                 => 1,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
	)
);


/**
 * Class tl_schachturnier_termine
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class tl_schachturnier_termine extends Backend
{

	var $nummer = 0;
	
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
	 * Listenansicht manipulieren
	 * @param array
	 * @param string
	 * @param \DataContainer
	 * @param array
	 * @return string
	 */
	public function listTermine($arrRow)
	{
		$temp = '<div class="tl_content_left">';
		$temp .= $arrRow['runde'].'. Runde';
		$temp .= ' am '.date('d.m.Y', $arrRow['datum']);
		return $temp.'</div>';
	}

	/**
	 * Datumswert aus Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	public function getNummer($varValue, DataContainer $dc)
	{
		if($varValue)
		{
			// Nummer vorhanden
			$temp = $varValue;
		}
		else
		{
			echo $dc->activeRecord->pid;
			// Keine Nummer vorhanden, nächste freie Nummer suchen
			$objNummer = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_termine WHERE pid=?")
			                                     ->execute($dc->activeRecord->pid);
			$nummern = array();
			while($objNummer->next())
			{
				$nummern[] = $objNummer->runde;
			}
			// Erste frei Nummer suchen
			$found = false;
			for($x = 1; $x < 1000; $x++)
			{
				if(!in_array($x, $nummern))
				{
					$found = $x;
					break;
				}
			}
			// Nichts gefunden?
			if(!$found) $found = count($nummern) + 1;
			$temp = $found;
		}
		return $temp;
	}

	/**
	 * Datumswert für Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	public function putNummer($varValue)
	{
		return $varValue;
	} 

}
