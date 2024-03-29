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
 * Table tl_schachturnier_spieler
 */
$GLOBALS['TL_DCA']['tl_schachturnier_spieler'] = array
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
			'fields'                  => array('ABS(nummer)', 'lastname ASC'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_schachturnier_spieler', 'listPlayers'),  
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
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['toggle'],
				'attributes'           => 'onclick="Backend.getScrollOffset()"',
				'haste_ajax_operation' => array
				(
					'field'            => 'published',
					'options'          => array
					(
						array('value' => '', 'icon' => 'invisible.svg'),
						array('value' => '1', 'icon' => 'visible.svg'),
					),
				),
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('addImage', 'ausgeschieden'),
		'default'                     => '{name_legend},firstname,lastname,nummer;{status_legend},freilos,herkunft,ausgeschieden;{qualifikationen_legend},unaufsteigbar,unabsteigbar,aufsteiger,absteiger;{rating_legend},dwz,elo,titel;{image_legend:hide},addImage;{info_legend:hide},info;{publish_legend},published'
	),

	// Unterpaletten
	'subpalettes' => array
	(
		'addImage'                    => 'singleSRC',
		'ausgeschieden'               => 'partienwertung',
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
		'firstname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['firstname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'lastname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['lastname'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'w50'),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'nummer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['nummer'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
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
				array('tl_schachturnier_spieler', 'getNummer')
			),
			'save_callback' => array
			(
				array('tl_schachturnier_spieler', 'putNummer')
			),
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'dwz' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['dwz'],
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
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'elo' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['elo'],
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
			'sql'                     => "varchar(4) NOT NULL default ''"
		),
		'titel' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['titel'],
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'inputType'               => 'select',
			'options'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['titel_options'],
			'eval'                    => array
			(
				'includeBlankOption'  => true,
				'mandatory'           => false, 
				'maxlength'           => 3,
				'tl_class'            => 'w50'
			),
			'sql'                     => "varchar(3) NOT NULL default ''"
		), 
		'ausgeschieden' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['ausgeschieden'],
			'default'                 => '',
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'doNotCopy'           => false,
				'mandatory'           => false, 
				'tl_class'            => 'w50 m12',
				'submitOnChange'      => true,
				'boolean'             => true
			),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'partienwertung' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['partienwertung'],
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'inputType'               => 'select',
			'options'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['partienwertung_options'],
			'eval'                    => array
			(
				'mandatory'           => false,
				'maxlength'           => 1,
				'tl_class'            => 'w50'
			),
			'sql'                     => "varchar(1) NOT NULL default '1'"
		), 
		'freilos' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['freilos'],
			'default'                 => '',
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'doNotCopy'           => false,
				'mandatory'           => false, 
				'tl_class'            => 'w50 m12',
				'boolean'             => true
			),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'herkunft' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['herkunft'],
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'inputType'               => 'checkboxWizard',
			'options'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['herkunft_options'],
			'eval'                    => array
			(
				'mandatory'           => false,
				'multiple'            => true,
				'tl_class'            => 'w50'
			),
			'sql'                     => "blob NULL"
		), 
		'unaufsteigbar' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['unaufsteigbar'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'doNotCopy'           => false,
				'mandatory'           => false, 
				'tl_class'            => 'w50 m12',
				'boolean'             => true
			),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'unabsteigbar' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['unabsteigbar'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'doNotCopy'           => false,
				'mandatory'           => false, 
				'tl_class'            => 'w50 m12',
				'boolean'             => true
			),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'aufsteiger' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['aufsteiger'],
			'default'                 => '',
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'doNotCopy'           => false,
				'mandatory'           => false, 
				'tl_class'            => 'w50 m12',
				'boolean'             => true
			),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'absteiger' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['absteiger'],
			'default'                 => '',
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'doNotCopy'           => false,
				'mandatory'           => false, 
				'tl_class'            => 'w50 m12',
				'boolean'             => true
			),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'addImage' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['addImage'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
		'singleSRC' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['singleSRC'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('filesOnly'=>true, 'extensions'=>Config::get('validImageTypes'), 'fieldType'=>'radio', 'mandatory'=>true),
			'sql'                     => "binary(16) NULL"
		), 
		'info' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['info'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('rte'=>'tinyMCE', 'helpwizard'=>true),
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NULL"
		), 
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['published'],
			'default'                 => 1,
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('doNotCopy'=>true),
			'sql'                     => "char(1) NOT NULL default ''"
		), 
	)
);


/**
 * Class tl_schachturnier_spieler
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class tl_schachturnier_spieler extends Backend
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
	 * Listenansicht manipulieren
	 * @param array
	 * @param string
	 * @param \DataContainer
	 * @param array
	 * @return string
	 */
	public function listPlayers($arrRow)
	{
		$temp = '<div class="tl_content_left">';
		$temp .= '<span style="display:inline-block; width:200px;">';
		$temp .= '['.$arrRow['nummer'].'] '; // Startnummer ausgeben
		// Ausgeschieden-Status mit Spielername
		if($arrRow['ausgeschieden']) $temp .= '<s>';
		if($arrRow['freilos']) $temp .= '<span style="color:red">'.$arrRow['lastname'].'</span>';
		else $temp .= $arrRow['lastname'].', '.$arrRow['firstname'];
		if($arrRow['ausgeschieden']) $temp .= '</s>';
		$temp .= '</span>';
		// Herkunft-Status
		$temp .= '<span style="display:inline-block; width:50px;">';
		$herkunft = unserialize($arrRow['herkunft']);
		$temp .= is_array($herkunft) ? '<b>'.implode(',', $herkunft).'</b>' : '';
		$temp .= '</span>';
		// Qualifikations-Status
		if($arrRow['unaufsteigbar']) $temp .= '<img src="bundles/contaoschachturnier/images/nicht_oben.png" title="Spieler kann/darf nicht aufsteigen">';
		if($arrRow['unabsteigbar']) $temp .= '<img src="bundles/contaoschachturnier/images/nicht_unten.png" title="Spieler kann/darf nicht absteigen">';
		if($arrRow['aufsteiger']) $temp .= '<img src="bundles/contaoschachturnier/images/oben.png" title="Spieler mu� aufsteigen (Zwangsaufstieg)">';
		if($arrRow['absteiger']) $temp .= '<img src="bundles/contaoschachturnier/images/unten.png" title="Spieler mu� absteigen (Zwangsabstieg)">';

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
			// Keine Nummer vorhanden, n�chste freie Nummer suchen
			$objNummer = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_spieler WHERE pid=?")
			                                     ->execute($dc->activeRecord->pid);
			$nummern = array();
			while($objNummer->next())
			{
				$nummern[] = $objNummer->nummer;
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
	 * Datumswert f�r Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	public function putNummer($varValue)
	{
		return $varValue;
	} 

}
