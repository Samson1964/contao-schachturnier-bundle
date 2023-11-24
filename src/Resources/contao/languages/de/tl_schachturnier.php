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
$GLOBALS['TL_LANG']['tl_schachturnier']['fromDate'] = array('Beginn', 'Beginndatum im Format TT.MM.JJJJ');
$GLOBALS['TL_LANG']['tl_schachturnier']['toDate'] = array('Ende', 'Endedatum im Format TT.MM.JJJJ (kann leerbleiben bei einem eintägigen Turnier)');
$GLOBALS['TL_LANG']['tl_schachturnier']['fromDateView'] = array('Beginndatum anzeigen', 'Beginndatum im Frontend unter Tabellen anzeigen.');
$GLOBALS['TL_LANG']['tl_schachturnier']['toDateView'] = array('Endedatum anzeigen', 'Endedatum im Frontend unter Tabellen anzeigen.');

$GLOBALS['TL_LANG']['tl_schachturnier']['wertungen_legend'] = 'Wertungsreihenfolge';
$GLOBALS['TL_LANG']['tl_schachturnier']['wertungen'] = array('Wertungen aktivieren', 'Legen Sie hier die Wertungen und deren Reihenfolge fest.');

$GLOBALS['TL_LANG']['tl_schachturnier']['aufabstieg_legend'] = 'Auf- und Absteiger';
$GLOBALS['TL_LANG']['tl_schachturnier']['aufsteiger'] = array('Aufsteiger', 'Anzahl der Aufsteiger. Die ersten x Plätze in Ranglisten werden grün markiert. In den Einstellungen eines Spielers können abweichende Optionen aktiviert werden.');
$GLOBALS['TL_LANG']['tl_schachturnier']['absteiger'] = array('Absteiger', 'Anzahl der Absteiger. Die letzten x Plätze in Ranglisten werden rot markiert. In den Einstellungen eines Spielers können abweichende Optionen aktiviert werden.');

$GLOBALS['TL_LANG']['tl_schachturnier']['publish_legend'] = 'Veröffentlichung und Turnierabschluß';
$GLOBALS['TL_LANG']['tl_schachturnier']['complete'] = array('Komplett', 'Der Turnier ist vollständig erfaßt und alle Daten der Kindtabellen sind komplett.');
$GLOBALS['TL_LANG']['tl_schachturnier']['published'] = array('Veröffentlicht', 'Turnier veröffentlicht');

/**
 * Buttons für Operationen
 */

$GLOBALS['TL_LANG']['tl_schachturnier']['new'] = array('Neues Turnier', 'Neues Turnier anlegen');
$GLOBALS['TL_LANG']['tl_schachturnier']['edit'] = array('Turnier bearbeiten', 'Turnier %s bearbeiten');
$GLOBALS['TL_LANG']['tl_schachturnier']['copy'] = array('Turnier kopieren', 'Turnier %s kopieren');
$GLOBALS['TL_LANG']['tl_schachturnier']['delete'] = array('Turnier löschen', 'Turnier %s löschen');
$GLOBALS['TL_LANG']['tl_schachturnier']['toggle'] = array('Turnier aktivieren/deaktivieren', 'Turnier %s aktivieren/deaktivieren');
$GLOBALS['TL_LANG']['tl_schachturnier']['show'] = array('Turnierdetails anzeigen', 'Details des Turniers %s anzeigen');

$GLOBALS['TL_LANG']['tl_schachturnier']['editTermine'] = array('Termine bearbeiten', 'Termine des Turniers %s bearbeiten');
$GLOBALS['TL_LANG']['tl_schachturnier']['editSpieler'] = array('Spieler bearbeiten', 'Spieler des Turniers %s bearbeiten');
$GLOBALS['TL_LANG']['tl_schachturnier']['editPartien'] = array('Paarungen bearbeiten', 'Paarungen des Turniers %s bearbeiten');

/**
 * Buttons für Optionen
 */

$GLOBALS['TL_LANG']['tl_schachturnier']['typen'] = array
(
	'rd' => 'Rundenturnier',
	'ko' => 'K.o.-Turnier / Schweizer System',
);

$GLOBALS['TL_LANG']['tl_schachturnier']['wertungen_options'] = array
(
	'2punkte' => 'Zwei-Punkte-System (Sieg = 1, Remis = ½)',
	'3punkte' => 'Drei-Punkte-System (Sieg = 3, Remis = 1)',
	'sobe'    => 'Sonneborn-Berger-Wertung',
	'siege'   => 'Anzahl der Siege',
	'buch'    => 'Buchholz-Wertung',
);
