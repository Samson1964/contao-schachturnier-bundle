<?php 

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 */

// Eingabemaske
$GLOBALS['TL_LANG']['tl_schachturnier']['title_legend'] = 'Turniername und -Art';
$GLOBALS['TL_LANG']['tl_schachturnier']['title'] = array('Name', 'Name des Turniers');
$GLOBALS['TL_LANG']['tl_schachturnier']['type'] = array('Art', 'Art des Turniers');

$GLOBALS['TL_LANG']['tl_schachturnier']['date_legend'] = 'Zeitraum';
$GLOBALS['TL_LANG']['tl_schachturnier']['fromDate'] = array('Beginn', 'Beginndatum im Format JJJJ, MM.JJJJ oder TT.MM.JJJJ');
$GLOBALS['TL_LANG']['tl_schachturnier']['toDate'] = array('Ende', 'Endedatum im Format JJJJ, MM.JJJJ oder TT.MM.JJJJ (kann leerbleiben bei einem eintägigen Turnier)');

$GLOBALS['TL_LANG']['tl_schachturnier']['publish_legend'] = 'Veröffentlichung und Turnierabschluß';
$GLOBALS['TL_LANG']['tl_schachturnier']['complete'] = array('Komplett', 'Der Turnier ist vollständig erfaßt und alle Daten der Kindtabellen sind komplett.');
$GLOBALS['TL_LANG']['tl_schachturnier']['published'] = array('Veröffentlicht', 'Turnier veröffentlicht');

$GLOBALS['TL_LANG']['tl_schachturnier']['typen'] = array
(
	'rd' => 'Rundenturnier',
	'ko' => 'K.o.-Turnier / Schweizer System',
);

/**
 * Buttons für Operationen
 */

$GLOBALS['TL_LANG']['tl_schachturnier']['new'] = array('Neues Turnier', 'Neues Turnier anlegen');
$GLOBALS['TL_LANG']['tl_schachturnier']['edit'] = array('Turnier bearbeiten', 'Turnier %s bearbeiten');
$GLOBALS['TL_LANG']['tl_schachturnier']['copy'] = array('Turnier kopieren', 'Turnier %s kopieren');
$GLOBALS['TL_LANG']['tl_schachturnier']['delete'] = array('Turnier löschen', 'Turnier %s löschen');
$GLOBALS['TL_LANG']['tl_schachturnier']['toggle'] = array('Turnier aktivieren/deaktivieren', 'Turnier %s aktivieren/deaktivieren');
$GLOBALS['TL_LANG']['tl_schachturnier']['show'] = array('Turnierdetails anzeigen', 'Details des Turniers %s anzeigen');
