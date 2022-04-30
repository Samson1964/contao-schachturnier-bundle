<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   bdf
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2014
 */

$GLOBALS['BE_MOD']['content']['schachturnier'] = array
(
	'tables'         => array('tl_schachturnier', 'tl_schachturnier_spieler', 'tl_schachturnier_partien', 'tl_schachturnier_termine'),
	'pairs_generate' => array('Schachbulle\ContaoSchachturnierBundle\Classes\Paarungsgenerator', 'generatePairs'),
	'icon'           => 'bundles/contaoschachturnier/images/icon.png',
);

/**
 * -------------------------------------------------------------------------
 * CONTENT ELEMENTS
 * -------------------------------------------------------------------------
 */
$GLOBALS['TL_CTE']['schach']['schachturnier'] = 'Schachbulle\ContaoSchachturnierBundle\ContentElements\Schachturnier';

