<?php 

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 */

// Eingabemaske
$GLOBALS['TL_LANG']['tl_chesscompetition']['title_legend'] = 'Titel und Art';
$GLOBALS['TL_LANG']['tl_chesscompetition']['title'][0] = 'Name';
$GLOBALS['TL_LANG']['tl_chesscompetition']['title'][1] = 'Name des Wettbewerbs';
$GLOBALS['TL_LANG']['tl_chesscompetition']['type'][0] = 'Art';
$GLOBALS['TL_LANG']['tl_chesscompetition']['type'][1] = 'Art des Wettbewerbs';
$GLOBALS['TL_LANG']['tl_chesscompetition']['alias'][0] = 'Alias';
$GLOBALS['TL_LANG']['tl_chesscompetition']['alias'][1] = 'Der Alias wird automatisch generiert, wenn das Feld leer ist';

$GLOBALS['TL_LANG']['tl_chesscompetition']['place_legend'] = 'Ort';
$GLOBALS['TL_LANG']['tl_chesscompetition']['place'][0] = 'Ort';
$GLOBALS['TL_LANG']['tl_chesscompetition']['place'][1] = 'Ort in dem der Wettbewerb stattfand';
$GLOBALS['TL_LANG']['tl_chesscompetition']['country'][0] = 'Land';
$GLOBALS['TL_LANG']['tl_chesscompetition']['country'][1] = 'Land in dem der Wettbewerb stattfand';

$GLOBALS['TL_LANG']['tl_chesscompetition']['date_legend'] = 'Zeitraum';
$GLOBALS['TL_LANG']['tl_chesscompetition']['fromDate'][0] = 'Beginn';
$GLOBALS['TL_LANG']['tl_chesscompetition']['fromDate'][1] = 'Beginndatum im Format JJJJ, MM.JJJJ oder TT.MM.JJJJ';
$GLOBALS['TL_LANG']['tl_chesscompetition']['toDate'][0] = 'Ende';
$GLOBALS['TL_LANG']['tl_chesscompetition']['toDate'][1] = 'Endedatum im Format JJJJ, MM.JJJJ oder TT.MM.JJJJ (kann leerbleiben bei einem eintägigen Wettbewerb)';

$GLOBALS['TL_LANG']['tl_chesscompetition']['options_legend'] = 'Logo und Webadresse';
$GLOBALS['TL_LANG']['tl_chesscompetition']['singleSRC'][0] = 'Datei';
$GLOBALS['TL_LANG']['tl_chesscompetition']['singleSRC'][1] = 'Datei auswählen';
$GLOBALS['TL_LANG']['tl_chesscompetition']['url'][0] = 'Homepage';
$GLOBALS['TL_LANG']['tl_chesscompetition']['url'][1] = 'Internetadresse oder interne Seite';

$GLOBALS['TL_LANG']['tl_chesscompetition']['publish_legend'] = 'Veröffentlichung';
$GLOBALS['TL_LANG']['tl_chesscompetition']['complete'] = array('Komplett', 'Der Wettbewerb ist vollständig erfaßt und alle Daten der Kindtabellen sind komplett.');
$GLOBALS['TL_LANG']['tl_chesscompetition']['published'] = array('Veröffentlicht', 'Wettbewerb veröffentlicht');

$GLOBALS['TL_LANG']['tl_chesscompetition']['info_legend'] = 'Information';
$GLOBALS['TL_LANG']['tl_chesscompetition']['info'] = array('Information', 'Anmerkungen zum Wettbewerb');
$GLOBALS['TL_LANG']['tl_chesscompetition']['source'] = array('Quelle', 'Quelle der Daten dieses Wettbewerbs');

$GLOBALS['TL_LANG']['tl_chesscompetition']['typen'] = array
(
	'WM' => 'Weltmeisterschaft',
	'EM' => 'Europameisterschaft',
	'OL' => 'Olympiade',
	'MC' => 'Mitropacup',
	'LT' => 'Länderturnier',
	'LK' => 'Länderkampf',
);

/**
 * Buttons für Operationen
 */

$GLOBALS['TL_LANG']['tl_chesscompetition']['players'][0] = 'Spieler';
$GLOBALS['TL_LANG']['tl_chesscompetition']['players'][1] = 'Spielerverwaltung für die auszuwertenden Nationalspieler';

$GLOBALS['TL_LANG']['tl_chesscompetition']['new'][0] = 'Neuer Wettbewerb';
$GLOBALS['TL_LANG']['tl_chesscompetition']['new'][1] = 'Neuen Wettbewerb anlegen';

$GLOBALS['TL_LANG']['tl_chesscompetition']['edit'][0] = "Wettbewerb bearbeiten";
$GLOBALS['TL_LANG']['tl_chesscompetition']['edit'][1] = "Wettbewerb %s bearbeiten";

$GLOBALS['TL_LANG']['tl_chesscompetition']['copy'][0] = "Wettbewerb kopieren";
$GLOBALS['TL_LANG']['tl_chesscompetition']['copy'][1] = "Wettbewerb %s kopieren";

$GLOBALS['TL_LANG']['tl_chesscompetition']['delete'][0] = "Wettbewerb löschen";
$GLOBALS['TL_LANG']['tl_chesscompetition']['delete'][1] = "Wettbewerb %s löschen";

$GLOBALS['TL_LANG']['tl_chesscompetition']['toggle'][0] = "Wettbewerb aktivieren/deaktivieren";
$GLOBALS['TL_LANG']['tl_chesscompetition']['toggle'][1] = "Wettbewerb %s aktivieren/deaktivieren";

$GLOBALS['TL_LANG']['tl_chesscompetition']['show'][0] = "Wettbewerbdetails anzeigen";
$GLOBALS['TL_LANG']['tl_chesscompetition']['show'][1] = "Details des Wettbewerbs %s anzeigen";
