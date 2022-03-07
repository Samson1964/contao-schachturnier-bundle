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
			'headerFields'            => array('germanTeam', 'opponentTeam', 'round'),
			'panelLayout'             => 'filter;sort,search,limit',
			'child_record_callback'   => array('tl_schachturnier_partien', 'listGames'),  
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
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
				'button_callback'     => array('tl_schachturnier_partien', 'toggleIcon')
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
		'default'                     => '{player_legend},germanPlayer,opponentPlayer,germanRating,opponentRating,germanTitle,opponentTitle,germanColor;{results_legend:hide},result,board,info,source;{pgn_legend},pgn;{publish_legend},complete,published'
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
			'foreignKey'              => 'tl_chesscompetition_matches.pid',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'germanPlayer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['germanPlayer'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getGermanPlayer'),
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
		'opponentPlayer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['opponentPlayer'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getOpponentPlayer'),
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
		'germanRating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['germanRating'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'rgxp'                => 'digit', 
				'maxlength'           => 4,
				'tl_class'            => 'w50'
			),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'opponentRating' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['opponentRating'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'rgxp'                => 'digit', 
				'maxlength'           => 4,
				'tl_class'            => 'w50'
			),
			'sql'                     => "int(4) unsigned NOT NULL default '0'"
		),
		'germanTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['germanTitle'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getTitles'),
			'eval'                    => array
			(
				'chosen'              => true,
				'submitOnChange'      => false,
				'includeBlankOption'  => true,
				'tl_class'            => 'w50'
			),
			'sql'                     => "char(3) NOT NULL default ''"
		),
		'opponentTitle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['opponentTitle'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getTitles'),
			'eval'                    => array
			(
				'chosen'              => true,
				'submitOnChange'      => false,
				'includeBlankOption'  => true,
				'tl_class'            => 'w50'
			),
			'sql'                     => "char(3) NOT NULL default ''"
		),
		'germanColor' => array
		(
			'label'                 => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['germanColor'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'default'                 => '',
			'inputType'               => 'select',
			'options_callback'        => array('tl_schachturnier_partien', 'getColours'),
			'eval'                    => array
			(
				'includeBlankOption'  => true
			),
			'sql'                     => "char(1) NOT NULL default ''"
		),
		'result' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['result'],
			'exclude'                 => true,
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
		'board' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['board'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array
			(
				'rgxp'                => 'digit', 
				'tl_class'            => 'w50', 
				'mandatory'           => true, 
				'maxlength'           => 2
			),
			'sql'                     => "smallint(2) unsigned NOT NULL default '0'"
		), 
		'info' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['info'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => array
			(
				'rte'                 => 'tinyMCE', 
				'tl_class'            => 'long', 
				'helpwizard'          => true
			),
			'explanation'             => 'insertTags',
			'sql'                     => "mediumtext NULL"
		), 
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['source'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>255, 'tl_class'=>'long'),
			'sql'                     => "varchar(255) NOT NULL default ''"
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
		'complete' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_schachturnier_partien']['complete'],
			'exclude'                 => true,
			'filter'                  => true,
			'flag'                    => 1,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
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
				'doNotCopy'           => true
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
	
	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return ($this->User->isAdmin || count(preg_grep('/^tl_schachturnier_partien::/', $this->User->alexf)) > 0) ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ' : Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the link picker wizard
	 * @param \DataContainer
	 * @return string
	 */
	public function pagePicker(DataContainer $dc)
	{
		return ' <a href="contao/page.php?do=' . Input::get('do') . '&amp;table=' . $dc->table . '&amp;field=' . $dc->field . '&amp;value=' . str_replace(array('{{link_url::', '}}'), '', $dc->value) . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']) . '" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\'' . specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])) . '\',\'url\':this.href,\'id\':\'' . $dc->field . '\',\'tag\':\'ctrl_'. $dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '') . '\',\'self\':this});return false">' . Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}

	public function listGames($arrRow)
	{
		$temp = '<div class="tl_content_left">';
		$temp .= $arrRow['complete'] ? $this->generateImage('ok.gif', 'Partiedaten komplett') : $this->generateImage('delete.gif', 'Partiedaten nicht komplett');
		$temp .= '<b>['.$arrRow['board'].']</b>';
		// Mannschaftsnamen bauen
		$temp .= $arrRow['home'] ?  ' ' . $this->getPlayer($arrRow['germanPlayer']) : ' ' . $this->getPlayer($arrRow['opponentPlayer']); 
		$temp .= $arrRow['home'] ? ' - ' . $this->getPlayer($arrRow['opponentPlayer']) : ' - ' . $this->getPlayer($arrRow['germanPlayer']); 
		return $temp.'</div>';
	}

	/**
	 * Datumswert aus Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	public function getDate($varValue)
	{
		$laenge = strlen($varValue);
		$temp = '';
		switch($laenge)
		{
			case 8: // JJJJMMTT
				$temp = substr($varValue,6,2).'.'.substr($varValue,4,2).'.'.substr($varValue,0,4);
				break;
			case 6: // JJJJMM
				$temp = substr($varValue,4,2).'.'.substr($varValue,0,4);
				break;
			case 4: // JJJJ
				$temp = $varValue;
				break;
			default: // anderer Wert
				$temp = '';
		}

		return $temp;
	}

	/**
	 * Datumswert für Datenbank umwandeln
	 * @param mixed
	 * @return mixed
	 */
	public function putDate($varValue)
	{
		$laenge = strlen(trim($varValue));
		$temp = '';
		switch($laenge)
		{
			case 10: // TT.MM.JJJJ
				$temp = substr($varValue,6,4).substr($varValue,3,2).substr($varValue,0,2);
				break;
			case 7: // MM.JJJJ
				$temp = substr($varValue,3,4).substr($varValue,0,2);
				break;
			case 4: // JJJJ
				$temp = $varValue;
				break;
			default: // anderer Wert
				$temp = 0;
		}

		return $temp;
	}

	public function getPlayer($id)
	{

		$objPlayer = $this->Database->prepare("SELECT id, firstname, lastname FROM tl_chesscompetition_players WHERE id = ?")->execute($id);

		if($objPlayer->numRows == 1) return $objPlayer->firstname.' '.$objPlayer->lastname;
		else return $id;

	}

	public function getGermanPlayer(DataContainer $dc)
	{

		$arrForms = array();
		$objForms = $this->Database->prepare("SELECT id, firstname, lastname FROM tl_chesscompetition_players WHERE nationalPlayer = ? ORDER BY alias")->execute(1);

		while ($objForms->next())
		{
			$arrForms[$objForms->id] = $objForms->lastname .', '.$objForms->firstname. ' (ID ' . $objForms->id . ')';
		}

		return $arrForms;
	}

	public function getOpponentPlayer(DataContainer $dc)
	{

		$arrForms = array();
		$objForms = $this->Database->prepare("SELECT id, firstname, lastname FROM tl_chesscompetition_players ORDER BY alias")->execute();

		while ($objForms->next())
		{
			$arrForms[$objForms->id] = $objForms->lastname .', '.$objForms->firstname. ' (ID ' . $objForms->id . ')';
		}

		return $arrForms;
	}

	public function getTitles(DataContainer $dc)
	{
		$arrForms = array
		(
			'GM'   => 'Großmeister',
			'HM'   => 'Ehrengroßmeister',
			'IM'   => 'Internationaler Meister',
			'FM'   => 'FIDE-Meister',
			'CM'   => 'Kandidaten-Meister',
			'WGM'  => 'Frauen: Großmeisterin',
			'WIM'  => 'Frauen: Internationale Meisterin',
			'WFM'  => 'Frauen: FIDE-Meisterin',
			'WCM'  => 'Frauen: Kandidaten-Meisterin'
		);
		return $arrForms;
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

	public function getColours(DataContainer $dc)
	{
		$arrForms = array
		(
			'w'   => 'Weiß',
			's'   => 'Schwarz'
		);
		return $arrForms;
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_schachturnier_partien::published', 'alexf'))
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
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_schachturnier_partien::published', 'alexf'))
		{
			$this->log('Not enough permissions to show/hide record ID "'.$intId.'"', 'tl_schachturnier_partien toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
		
		$this->createInitialVersion('tl_schachturnier_partien', $intId);
		
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_schachturnier_partien']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_schachturnier_partien']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnPublished = $this->$callback[0]->$callback[1]($blnPublished, $this);
			}
		}
		
		// Update the database
		$this->Database->prepare("UPDATE tl_schachturnier_partien SET tstamp=". time() .", published='" . ($blnPublished ? '' : '1') . "' WHERE id=?")
		     ->execute($intId);
		$this->createNewVersion('tl_schachturnier_partien', $intId);
	}

}
