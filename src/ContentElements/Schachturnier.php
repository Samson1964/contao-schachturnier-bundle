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
						// Name generieren
						if($objResult->freilos) $name = $objResult->lastname;
						else $name = $objResult->titel ? $objResult->titel.' '.$objResult->firstname.' '.$objResult->lastname : $objResult->firstname.' '.$objResult->lastname;
						
						$daten[] = array
						(
							'css'    => $objResult->freilos ? 'freilos' : '',
							'nummer' => $nummer,
							'name'   => $name,
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
			case 'ranking'    : // Rangliste
				$this->strTemplate = 'ce_schachturnier_rangliste';
				$this->Template = new \FrontendTemplate($this->strTemplate);

				// Spieler initialisieren und Ergebnisse eintragen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::SpielerErgebnisse($this->schachturnier);
				// Sonneborn-Berger-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::SonnebornBergerWertung($spieler);
				// Buchholz-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::BuchholzWertung($spieler);

				// Turnier-Stammdaten laden
				$objTurnier = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier WHERE id = ?')
				                                      ->execute($this->schachturnier);
				// Spieler sortieren nach gewünschter Wertungsreihenfolge
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Rangliste($spieler, unserialize($objTurnier->wertungen));

				// Auf- und Absteiger markieren
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::AufAbsteiger($spieler, $objTurnier->aufsteiger, $objTurnier->absteiger);

				// Ausgabedaten zusammenbauen
				$daten = $spieler;
				break;

			case 'cross_nr'   : // Kreuztabelle (nach Nummern)
				$spieler = self::getSpieler();
				break;
			case 'cross_rang' : // Kreuztabelle (nach Rang)
				$this->strTemplate = 'ce_schachturnier_kreuztabelle';
				$this->Template = new \FrontendTemplate($this->strTemplate);

				$tabelle = new \Schachbulle\ContaoSchachturnierBundle\Classes\Tabelle($this->schachturnier);

				// Spieler initialisieren und Ergebnisse eintragen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::SpielerErgebnisse($this->schachturnier);
				// Sonneborn-Berger-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::SonnebornBergerWertung($spieler);
				// Buchholz-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::BuchholzWertung($spieler);

				// Turnier-Stammdaten laden
				$objTurnier = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier WHERE id = ?')
				                                      ->execute($this->schachturnier);
				// Spieler sortieren nach gewünschter Wertungsreihenfolge
				if($spieler)
				{
					$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Rangliste($spieler, unserialize($objTurnier->wertungen));
				}

				// Auf- und Absteiger markieren
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::AufAbsteiger($spieler, $objTurnier->aufsteiger, $objTurnier->absteiger);

				// Ergebnisse als Kreuztabelle eintragen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Ergebnismatrix($spieler);

		//echo "<pre>";
		//print_r($spieler);
		//echo "</pre>";

				
				// Ausgabedaten zusammenbauen
				$daten = $spieler;
				$daten = $tabelle->getTabelle();
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
						// Spielfrei markieren
						if($spieler[$objResult->whiteName]['freilos'])
						{
							$weiss = $spieler[$objResult->whiteName]['nachname'];
							$css = 'freilos';
						}
						elseif($spieler[$objResult->blackName]['freilos'])
						{
							$schwarz = $spieler[$objResult->blackName]['nachname'];
							$css = 'freilos';
						}
						else $css = '';

						$paarung[$objResult->round][$objResult->board] = array
						(
							'css'            => $css,
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
					//$paarung = self::Spielfreisuche($paarung, $spieler);
				}

				// Ausgabedaten zusammenbauen
				$daten = $paarung;
				$this->Template->termine = $termin;
		
				break;
			default:
		}

		// Ausgabe Turnierdatum generieren
		$turnierdatum = '';
		if(isset($objTurnier))
		{
			if($objTurnier->fromDateView)
			{
				$turnierdatum .= $GLOBALS['TL_LANG']['tl_schachturnier']['turnierbeginntext'].date('d.m.Y', $objTurnier->fromDate);
			}
			if($objTurnier->toDateView)
			{
				if($turnierdatum) $turnierdatum .= $GLOBALS['TL_LANG']['tl_schachturnier']['turnierdatumtrenner'];
				$turnierdatum .= $GLOBALS['TL_LANG']['tl_schachturnier']['turnierendetext'].date('d.m.Y', $objTurnier->toDate);
			}
		}

		// Template ausgeben
		$this->Template->class = "ce_schachturnier";
		$this->Template->turnierdatum = $turnierdatum;
		$this->Template->tabelle = $daten;
		$this->Template->view_land = in_array('land', $view);
		$this->Template->view_elo = in_array('elo', $view);
		$this->Template->view_dwz = in_array('dwz', $view);
		$this->Template->view_verein = in_array('verein', $view);

		return;

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
				return '<span class="abgesagt" title="Spieler ist entschuldigt">'.$name.'</span> (E)';
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
					'vorname'       => $objResult->firstname,
					'nachname'      => $objResult->lastname,
					'titel'         => $objResult->titel,
					'land'          => $objResult->land,
					'verein'        => $objResult->verein,
					'ausgeschieden' => $objResult->ausgeschieden,
					'freilos'       => $objResult->freilos,
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

	function getPaarungen()
	{
		// Spieler laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_partien WHERE pid = ? AND published = ?')
		                                     ->execute($this->schachturnier, 1);
		
		$paarungen = array();
		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
			{
				$paarungen[$objResult->id] = array
				(
					'whiteName'     => $objResult->whiteName,
					'blackName'     => $objResult->blackName,
					'round'         => $objResult->round,
					'board'         => $objResult->board,
					'datum'         => $objResult->datum,
					'result'        => $objResult->result,
					'info'          => $objResult->info,
					'pgn'           => $objResult->pgn
				);
			}
		}
		return $paarungen;
	}

}
