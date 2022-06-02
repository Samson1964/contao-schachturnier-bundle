<?php 

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 */

// Eingabemaske
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['name_legend'] = 'Vor- und Nachname';
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['firstname'] = array('Vorname', 'Vorname des Spielers');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['lastname'] = array('Nachname', 'Nachname des Spielers');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['nummer'] = array('Startnummer', 'Startnummer des Spielers. Bei neuen Spielern wird die nächste freie Nummer vorgeschlagen.');

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['status_legend'] = 'Status';
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['ausgeschieden'] = array('Ausgeschieden', 'Der Spieler ist aus dem Turnier ausgeschieden und wird in Listen durchgestrichen dargestellt.');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['freilos'] = array('Freilos', 'Spieler als Dummy-Eintrag (Freilos) führen. Der Nachname wird für die Anzeige übernommen, der Vorname ignoriert.');

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['qualifikationen_legend'] = 'Qualifikationen';
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['unaufsteigbar'] = array('Unaufsteigbar', 'Der Spieler darf nicht aufsteigen bzw. nicht Meister werden.');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['unabsteigbar'] = array('Unabsteigbar', 'Der Spieler darf nicht absteigen.');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['aufsteiger'] = array('Aufsteiger', 'Spieler als Aufsteiger markieren. In Ranglisten werden Aufsteiger grün markiert.');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['absteiger'] = array('Absteiger', 'Spieler als Absteiger markieren. In Ranglisten werden Absteiger rot markiert.');

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['rating_legend'] = 'Wertungszahlen';
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['dwz'] = array('DWZ', 'Deutsche Wertungszahl');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['elo'] = array('Elo', 'FIDE-Wertungszahl');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['titel'] = array('FIDE-Titel', 'FIDE-Titel');

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['image_legend'] = 'Bild';
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['addImage'] = array('Ein Bild hinzufügen', 'Ein Bild des Spielers hinzufügen.');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['singleSRC'] = array('Quelldatei', 'Bitte wählen Sie eine Datei aus der Dateiübersicht.');

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['info_legend'] = 'Intern';
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['info'] = array('Bemerkungen', 'Interne Bemerkungen zum Spieler');

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['publish_legend'] = 'Veröffentlichung';
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['published'] = array('Veröffentlicht', 'Spieler veröffentlicht');

/**
 * Buttons für Operationen
 */

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['new'] = array('Neuer Spieler', 'Neuen Spieler anlegen');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['edit'] = array('Spieler bearbeiten', 'Spieler %s bearbeiten');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['copy'] = array('Spieler kopieren', 'Spieler %s kopieren');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['delete'] = array('Spieler löschen', 'Spieler %s löschen');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['toggle'] = array('Spieler aktivieren/deaktivieren', 'Spieler %s aktivieren/deaktivieren');
$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['show'] = array('Spielerdetails anzeigen', 'Details des Spielers %s anzeigen');

$GLOBALS['TL_LANG']['tl_schachturnier_spieler']['titel_options'] = array
(
	'GM'  => 'Großmeister',
	'IM'  => 'Internationaler Meister',
	'FM'  => 'FIDE-Meister',
	'CM'  => 'Kandidatenmeister',
	'WGM' => 'Großmeisterin',
	'WIM' => 'Internationale Meisterin',
	'WFM' => 'FIDE-Meisterin',
	'WCM' => 'Kandidatenmeisterin',
);
