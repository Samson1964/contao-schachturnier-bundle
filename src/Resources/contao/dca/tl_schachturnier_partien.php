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
 * Table tl_schachturnier_partien
 */
$GLOBALS['TL_DCA']['tl_schachturnier_partien'] = array
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
				'id'  => 'primary',
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
			'fields'                  => array('-round DESC', 'board ASC'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_schachturnier_partien', 'listGames'),
		),
		'global_operations' => array
		(
			'pairs_generate' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['pairs_generate'],
				'href'                => 'key=pairs_generate',
				'icon'                => 'bundles/contaoschachturnier/images/pairs.png',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['tl_schachturnier_partien']['pairs_generate_confirm'] . '\'))return false"',
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif',
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif'
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['toggle'],
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
				'label'               => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{player_legend},whiteName,blackName,round,board,datum;{absagen_legend:hide},absagen;{results_legend:hide},result,info;{pgn_legend},pgn;{publish_legend},published'
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
		'whiteName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['whiteName'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getPlayers'),
			'eval'                    => array
			(
				'mandatory'           => false,
				'chosen'              => true,
				'includeBlankOption'  => true,
				'submitOnChange'      => false,
				'tl_class'            => 'w50'
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'blackName' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['blackName'],
			'exclude'                 => true,
			'filter'                  => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getPlayers'),
			'eval'                    => array
			(
				'mandatory'           => false,
				'chosen'              => true,
				'includeBlankOption'  => true,
				'submitOnChange'      => false,
				'tl_class'            => 'w50'
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'round' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['round'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => false,
			'sorting'                 => false,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'mandatory'           => false,
				'maxlength'           => 3,
				'tl_class'            => 'w50'
			),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'board' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['board'],
			'exclude'                 => true,
			'search'                  => false,
			'sorting'                 => false,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'mandatory'           => false,
				'maxlength'           => 3,
				'tl_class'            => 'w50'
			),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'datum' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['datum'],
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
				array('tl_schachturnier_partien', 'loadDate')
			),
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'absagen' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['absagen'],
			'exclude'                 => true,
			'inputType'               => 'multiColumnWizard',
			'eval'                    => array
			(
				'tl_class'            => 'clr',
				'buttonPos'           => 'top',
				'columnFields'        => array
				(
					'datum' => array
					(
						'label'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['absagen_datum'],
						'exclude'               => true,
						'inputType'             => 'text',
						'eval'                  => array
						(
							'maxlength'         => 10,
							'style'             => 'width: 100px',
							'valign'            => 'middle',
							'rgxp'              => 'date',
							'datepicker'        => true

						)
					),
					'wer' => array
					(
						'label'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['absagen_wer'],
						'exclude'               => true,
						'inputType'             => 'select',
						'options'               => array('white' => 'Weiß-Spieler', 'black' => 'Schwarz-Spieler'),
						'eval'                  => array
						(
							'style'             => 'width: 200px',
						),
					),
					'bemerkung' => array
					(
						'label'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['absagen_bemerkung'],
						'exclude'               => true,
						'inputType'             => 'text',
						'eval'                  => array
						(
							'style'             => 'width: 400px',
						)
					),
					'aktiv' => array
					(
						'label'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['absagen_aktiv'],
						'exclude'               => true,
						'inputType'             => 'checkbox',
						'eval'                  => array
						(
							'boolean'           => true,
							'style'             => 'width: 20px',
							'valign'            => 'middle'
						)
					),
				)
			),
			'sql'                   => "blob NULL"
		),
		'result' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['result'],
			'exclude'                 => true,
			'filter'                  => true,
			'search'                  => false,
			'sorting'                 => false,
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getResults'),
			'eval'                    => array
			(
				'includeBlankOption'  => true,
				'mandatory'           => false,
				'maxlength'           => 10,
				'tl_class'            => 'w50'
			),
			'sql'                     => "varchar(3) NOT NULL default ''"
		),
		'info' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['info'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array
			(
				'tl_class'            => 'w50',
				'helpwizard'          => true
			),
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NULL"
		),
		'pgn' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['pgn'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array('tl_class'=>'clr'),
			'explanation'             => 'insertTags',
			'sql'                     => 'text NULL'
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['published'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'default'                 => 1,
			'inputType'               => 'checkbox',
			'eval'                    => array
			(
				'doNotCopy'           => true,
				'boolean'             => true,
			),
			'sql'                     => "char(1) NOT NULL default ''"
		),
	)
);


/**
 * Class tl_schachturnier_partien
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    News
 */
class tl_schachturnier_partien extends Backend
{

	var $nummer = 0;
	var $player = array();

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
		if($value) return strtotime(date('Y-m-d', $value) . ' 00:00:00');
		else return '';
	}

	public function listGames($arrRow)
	{
		static $runde; // Speichert die aktuelle Runde

		$weiss = self::getPlayer($arrRow['whiteName']);
		$schwarz = self::getPlayer($arrRow['blackName']);

		$trenner = ' - ';
		// Ergebnisfarbe grün/rot
		if($arrRow['result'])
		{
			$css = 'color:green;';
			$trenner = ' '.$arrRow['result'].' ';
		}
		elseif(!$weiss || !$schwarz) $css = 'color:green;';
		else $css = 'color:red;';

		// Rundenfarbe
		if($runde && ($arrRow['round'] != $runde))
		{
			$css .= 'padding-top:5px;border-top:1px solid black;';
		}
		
		$temp = '<div class="tl_content_left" style="'.$css.'">';
		$temp .= '<span style="display:inline-block; width:5%;">'.$arrRow['round'].'.'.$arrRow['board'].'</span>';
		if($weiss && $schwarz)
		{
			$temp .= '<span style="display:inline-block; width:20%;">'.$weiss.'</span>';
			$temp .= '<span style="display:inline-block; width:10%;">'.$trenner.'</span>';
			$temp .= '<span style="display:inline-block; width:20%;">'.$schwarz.'</span>';
		}
		elseif($weiss)
		{
			$temp .= '<span style="display:inline-block; width:20%; font-weight:bold">Spielfrei:</span>';
			$temp .= '<span style="display:inline-block; width:10%;"></span>';
			$temp .= '<span style="display:inline-block; width:20%;">'.$weiss.'</span>';
		}
		elseif($schwarz)
		{
			$temp .= '<span style="display:inline-block; width:20%; font-weight:bold">Spielfrei:</span>';
			$temp .= '<span style="display:inline-block; width:10%;"></span>';
			$temp .= '<span style="display:inline-block; width:20%;">'.$schwarz.'</span>';
		}

		$runde = $arrRow['round'];

		return $temp.'</div>';
	}

	public function getPlayers(\DataContainer $dc)
	{
		if($dc->activeRecord)
		{
			// Aktiver Datensatz wurde übergeben
			$pid = $dc->activeRecord->pid;
		}
		else
		{
			$pid = \Input::get('id');
		}

		$arrPlayer = array();
		$objPlayer = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_spieler WHERE pid=? ORDER BY nummer ASC")
		                                     ->execute($pid);

		while($objPlayer->next())
		{
			$arrPlayer[$objPlayer->id] = '('.$objPlayer->nummer.') '.$objPlayer->firstname .' '.$objPlayer->lastname;
			if($objPlayer->ausgeschieden) $arrPlayer[$objPlayer->id] = '<strike>'.$arrPlayer[$objPlayer->id].'</strike>';
		}

		return $arrPlayer;
	}

	public function getPlayer($id)
	{

		$objPlayer = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_spieler WHERE id = ?")
		                                     ->execute($id);

		if($objPlayer->numRows)
		{
			if($objPlayer->freilos) $name = '';
			else 
			{
				$name = '('.$objPlayer->nummer.') '.$objPlayer->firstname.' '.$objPlayer->lastname;
				if($objPlayer->ausgeschieden) $name = '<strike>'.$name.'</strike>';
			}
			return $name;
		}
		else return $id;

	}

	public function getResults(DataContainer $dc)
	{
		$arrForms = array
		(
			'1:0'  => '1:0',
			'0:1'  => '0:1',
			'½:½'  => '½:½',
			'+:-'  => '+:-',
			'-:+'  => '-:+',
			'-:-'  => '-:-'
		);
		return $arrForms;
	}

}
