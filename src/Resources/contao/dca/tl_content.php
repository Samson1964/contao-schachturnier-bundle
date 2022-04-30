<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   fen
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_content']['palettes']['schachturnier'] = '{type_legend},type,headline;{schachturnier_legend},schachturnier,schachturnier_mode,schachturnier_options;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID;{invisible_legend:hide},invisible,start,stop';

/**
 * Fields
 */

$GLOBALS['TL_DCA']['tl_content']['fields']['schachturnier'] = array
(
	'label'                            => &$GLOBALS['TL_LANG']['tl_content']['schachturnier'],
	'exclude'                          => true,
	'inputType'                        => 'select',
	'options_callback'                 => array('tl_content_schachturnier', 'getChesstournament'),
	'eval'                             => array
	(
		'tl_class'                     => 'long', 
		'submitOnChange'               => true, 
		'chosen'                       => true
	),
	'sql'                              => "int(10) unsigned NOT NULL default '0'"
); 

$GLOBALS['TL_DCA']['tl_content']['fields']['schachturnier_mode'] = array
(
	'label'                            => &$GLOBALS['TL_LANG']['tl_content']['schachturnier_mode'],
	'exclude'                          => true,
	'inputType'                        => 'select',
	'default'                          => 'subscriber',
	'options'                          => array
	(
		'subscriber'                   => 'Teilnehmerliste',
		'cross_nr'                     => 'Kreuztabelle (nach Nummer)', 
		'cross_rang'                   => 'Kreuztabelle (nach Rang)', 
		'progress_nr'                  => 'Fortschrittstabelle (nach Nummer)',
		'progress_rang'                => 'Fortschrittstabelle (nach Rang)',
		'pairings'                     => 'Paarungen/Ergebnisse'
	),
	'eval'                             => array
	(
		'tl_class'                     => 'w50', 
		'submitOnChange'               => false, 
		'chosen'                       => true
	),
	'sql'                              => "varchar(13) NOT NULL default ''"
); 

$GLOBALS['TL_DCA']['tl_content']['fields']['schachturnier_options'] = array
(
	'label'                            => &$GLOBALS['TL_LANG']['tl_content']['schachturnier_options'],
	'exclude'                          => true,
	'inputType'                        => 'checkbox',
	'options'                          => &$GLOBALS['TL_LANG']['tl_content']['schachturnier_optionsfelder'],
	'eval'                             => array('multiple'=>true, 'tl_class'=>'w50'),
	'sql'                              => "text NULL"
);

class tl_content_schachturnier extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	public function getChesstournament(DataContainer $dc)
	{
		$array = array();
		$objTurniere = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier ORDER BY fromDate DESC")
		                                       ->execute();
		while($objTurniere->next())
		{
			$array[$objTurniere->id] =  $objTurniere->title;
		}
		return $array;

	}
}
