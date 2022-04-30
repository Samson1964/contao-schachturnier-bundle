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
							'datum'      => \Schachbulle\ContaoHelperBundle\Classes\Helper::getDate($objResult->datum),
						);
					}
				}

				// Paarungen laden
				$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_partien WHERE pid = ? AND published = ?')
				                                     ->execute($this->schachturnier, 1);
				$paarung = array();
				if($objResult->numRows)
				{
					// Datensätze verarbeiten
					while($objResult->next())
					{
						$paarung[$objResult->round][$objResult->board] = array
						(
							'weiss_nr'     => $objResult->whiteName,
							'weiss_name'   => $spieler[$objResult->whiteName]['name'],
							'weiss_dwz'    => $spieler[$objResult->whiteName]['dwz'],
							'schwarz_nr'   => $objResult->blackName,
							'schwarz_name' => $spieler[$objResult->blackName]['name'],
							'schwarz_dwz'  => $spieler[$objResult->blackName]['dwz'],
							'datum'        => $objResult->datum && $objResult->datum != $termin[$objResult->round] ? \Schachbulle\ContaoHelperBundle\Classes\Helper::getDate($objResult->datum) : '',
							'ergebnis'     => $objResult->result ? $objResult->result : '-',
							'info'         => $objResult->info,
						);
					}
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

}
