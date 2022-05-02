<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * @package   chesstable
 * Version    1.0.0
 * @author    Frank Hoppe
 * @license   GNU/LGPL
 * @copyright Frank Hoppe 2013
 */
namespace Schachbulle\ContaoSchachturnierBundle\ContentElements;

class Schachturnier extends \ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_schachturnier';

	/**
	 * Generate the module
	 */
	protected function compile()
	{

		// Optionen laden
		$view = unserialize($this->schachturnier_options);
		
		switch($this->schachturnier_mode)
		{
			case 'subscriber': // Teilnehmerliste
				$this->strTemplate = 'ce_schachturnier_teilnehmer';
				$this->Template = new \FrontendTemplate($this->strTemplate);
				$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? ORDER BY nummer ASC')
				                                     ->execute($this->schachturnier);
				$daten = array();
				if($objResult->numRows)
				{
					$nummer = 0;
					// Datensätze verarbeiten
					while($objResult->next())
					{
						$nummer++;
						$daten[] = array
						(
							'nummer' => $nummer,
							'name'   => $objResult->titel ? $objResult->titel.' '.$objResult->firstname.' '.$objResult->lastname : $objResult->firstname.' '.$objResult->lastname,
							'titel'  => $objResult->titel,
							'land'   => $objResult->land,
							'verein' => $objResult->verein,
							'dwz'    => $objResult->dwz,
							'elo'    => $objResult->elo,
							'bild'   => $objResult->singleSRC
						);
					}
				}
				break;
			case 'cross_nr'   : // Kreuztabelle (nach Nummern)
				break;
			case 'cross_rang' : // Kreuztabelle (nach Rang)
				break;
			case 'progress_nr': // Fortschrittstabelle (nach Nummern)
				break;
			case 'progress_nr': // Fortschrittstabelle (nach Rang)
				break;
			case 'pairings'   : // Paarungen (alle Runden)
				$this->strTemplate = 'ce_schachturnier_paarungen';
				$this->Template = new \FrontendTemplate($this->strTemplate);

				$spieler = self::getSpieler();
				$termin = self::getTermine();

				// Paarungen laden
				$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_partien WHERE pid = ? AND published = ?')
				                                     ->execute($this->schachturnier, 1);
				$paarung = array();
				if($objResult->numRows)
				{
					// Datensätze verarbeiten
					while($objResult->next())
					{
						// Spielernamen erzeugen inkl. Ausgeschieden-Markierung
						$weiss = $spieler[$objResult->whiteName]['ausgeschieden'] ? '<s>'.$spieler[$objResult->whiteName]['name'].'</s>' : $spieler[$objResult->whiteName]['name'];
						$schwarz = $spieler[$objResult->blackName]['ausgeschieden'] ? '<s>'.$spieler[$objResult->blackName]['name'].'</s>' : $spieler[$objResult->blackName]['name'];
						// Absagen markieren
						$weiss = self::Absage($weiss, $objResult->absagen, 'white');
						$schwarz = self::Absage($schwarz, $objResult->absagen, 'black');

						$paarung[$objResult->round][$objResult->board] = array
						(
							'weiss_id'       => $objResult->whiteName,
							'weiss_name'     => $weiss,
							'weiss_dwz'      => $spieler[$objResult->whiteName]['dwz'],
							'weiss_nummer'   => $spieler[$objResult->whiteName]['nummer'],
							'schwarz_id'     => $objResult->blackName,
							'schwarz_name'   => $schwarz,
							'schwarz_dwz'    => $spieler[$objResult->blackName]['dwz'],
							'schwarz_nummer' => $spieler[$objResult->blackName]['nummer'],
							'datum'          => $objResult->datum ? date('d.m.Y', $objResult->datum) : '',
							'ergebnis'       => $objResult->result ? $objResult->result : '-',
							'info'           => $objResult->info,
						);
					}
					// Spielfrei ergänzen
					$paarung = self::Spielfreisuche($paarung, $spieler);
				}

				// Ausgabedaten zusammenbauen
				$daten = $paarung;
				$this->Template->termine = $termin;
		
				break;
			default:
		}

		// Template ausgeben
		$this->Template->class = "ce_schachturnier";
		$this->Template->tabelle = $daten;
		$this->Template->view_land = in_array('land', $view);
		$this->Template->view_elo = in_array('elo', $view);
		$this->Template->view_dwz = in_array('dwz', $view);
		$this->Template->view_verein = in_array('verein', $view);

		return;

	}

	function Spielfreisuche($paarung, $spieler)
	{
		// Spieler laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? AND published = ?')
		                                     ->execute($this->schachturnier, 1);
		if($objResult->numRows % 2 > 0)
		{
			// Ungerade Teilnehmerzahl, dann nach spielfrei suchen
			// Array für Teilnehmernummern für alle Runden anlegen
			$spielfrei = array();
			
			foreach($paarung as $runde => $arrBrett)
			{
				if(!isset($spielfrei[$runde]))
				{
					// Teilnehmerliste für diese Runde anlegen
					for($x = 1; $x <= $objResult->numRows; $x++)
					{
						$spielfrei[$runde][$x] = true;
					}
				}
				foreach($arrBrett as $brett => $item)
				{
					$spielfrei[$runde][$item['weiss_nummer']] = false;
					$spielfrei[$runde][$item['schwarz_nummer']] = false;
				}
			}
			// In den Paarungen spielfrei ergänzen
			foreach($spielfrei as $runde => $arrNummer)
			{
				foreach($arrNummer as $nummer => $item)
				{
					if($item)
					{
						// Spieler laden
						$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? AND nummer = ?')
						                                     ->execute($this->schachturnier, $nummer);
						if($objResult->numRows)
						{
							// Spielernamen erzeugen inkl. Ausgeschieden-Markierung
							$weiss = $objResult->ausgeschieden ? '<s>'.$objResult->firstname.' '.$objResult->lastname.'</s>' : $objResult->firstname.' '.$objResult->lastname;
							$schwarz = 'spielfrei';
							
							$paarung[$runde][] = array
							(
								'weiss_id'       => $objResult->id,
								'weiss_name'     => $weiss,
								'weiss_dwz'      => $objResult->dwz,
								'weiss_nummer'   => $objResult->nummer,
								'schwarz_id'     => 0,
								'schwarz_name'   => $schwarz,
								'schwarz_dwz'    => '',
								'schwarz_nummer' => '',
								'datum'          => '',
								'ergebnis'       => '',
								'info'           => '',
							);
						}
					}
				}
			}
		}
		return $paarung;
	}

	function Absage($name, $absagen, $farbe)
	{
		if($absagen)
		{
			$absage = (array)unserialize($absagen);
			$absagedatum = 0;
			$absageaktiv = false;
			// Absagen durchsuchen nach Farbe und jüngste, aktive Absage speichern
			foreach($absage as $item)
			{
				if($item['wer'] == $farbe && $item['aktiv'])
				{
					if($item['datum'] > $absagedatum)
					{
						$absagedatum = $item['datum'];
						$absageaktiv = true;
					}
				}
			}
			if($absageaktiv)
			{
				return $name.' (E)';
			}
		}
		return $name;
	}

	function getSpieler()
	{
		// Spieler laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? AND published = ?')
		                                     ->execute($this->schachturnier, 1);
		
		$spieler = array();
		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
			{
				$spieler[$objResult->id] = array
				(
					'name'          => $objResult->titel ? $objResult->titel.' '.$objResult->firstname.' '.$objResult->lastname : $objResult->firstname.' '.$objResult->lastname,
					'titel'         => $objResult->titel,
					'land'          => $objResult->land,
					'verein'        => $objResult->verein,
					'ausgeschieden' => $objResult->ausgeschieden,
					'dwz'           => $objResult->dwz,
					'elo'           => $objResult->elo,
					'nummer'        => $objResult->nummer,
					'bild'          => $objResult->singleSRC
				);
			}
		}
		return $spieler;
	}

	function getTermine()
	{
		// Termine laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_termine WHERE pid = ? AND published = ?')
		                                     ->execute($this->schachturnier, 1);
		
		$termin = array();
		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
			{
				$termin[$objResult->runde] = array
				(
					'datum'      => date('d.m.Y', $objResult->datum),
				);
			}
		}
		return $termin;
	}
}
