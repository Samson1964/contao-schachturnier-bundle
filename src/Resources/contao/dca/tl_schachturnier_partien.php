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
			'fields'                  => array('round ASC', 'board ASC'),
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
		'default'                     => '{player_legend},whiteName,blackName,round,board,datum;{results_legend:hide},result,info;{pgn_legend},pgn;{publish_legend},published'
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
		return strtotime(date('Y-m-d', $value) . ' 00:00:00');
	}

	public function listGames($arrRow)
	{
		$temp = '<div class="tl_content_left">';
		$temp .= '<span style="display:inline-block; width:100px;">'.$arrRow['round'].'.'.$arrRow['board'].'</span>';
		$temp .= self::getPlayer($arrRow['whiteName']).' - '.self::getPlayer($arrRow['blackName']);
		return $temp.'</div>';
	}

	public function getPlayers(DataContainer $dc)
	{

		$arrForms = array();
		$objForms = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_spieler WHERE pid=? ORDER BY nummer ASC")
		                                    ->execute($dc->activeRecord->pid);

		while($objForms->next())
		{
			$arrForms[$objForms->id] = '('.$objForms->nummer.') '.$objForms->firstname .' '.$objForms->lastname;
		}

		return $arrForms;
	}

	public function getPlayer($id)
	{

		$objPlayer = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_spieler WHERE id = ?")
		                                     ->execute($id);

		if($objPlayer->numRows == 1) return '('.$objPlayer->nummer.') '.$objPlayer->firstname.' '.$objPlayer->lastname;
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
