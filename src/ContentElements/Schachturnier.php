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

		// Symlink für das externe Bundle components/flag-icon-css erstellen, wenn noch nicht vorhanden
		if(!is_link(TL_ROOT.'/web/bundles/flag-icon-css')) symlink(TL_ROOT.'/vendor/components/flag-icon-css/', TL_ROOT.'/web/bundles/flag-icon-css'); // Ziel, Name
		$GLOBALS['TL_CSS'][] = 'bundles/flag-icon-css/css/flag-icon.min.css';

		// Turnier-Objekt laden
		$objTurnier = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier WHERE id=?')
		                                      ->execute($this->schachturnier);
		// Spieler-Objekt laden
		$objSpieler = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? AND published = ? ORDER BY nummer ASC')
		                                      ->execute($this->schachturnier, 1);

		// Inhaltselement-Optionen laden
		$view = (array)unserialize($this->schachturnier_options);
		
		switch($this->schachturnier_mode)
		{
			case 'subscriber': // Teilnehmerliste

				$this->strTemplate = 'ce_schachturnier_teilnehmer';
				$this->Template = new \FrontendTemplate($this->strTemplate);
				$daten = array();

				if($objSpieler->numRows)
				{
					$nummer = 0;
					// Datensätze verarbeiten
					while($objSpieler->next())
					{
						$nummer++;
						$daten[] = array
						(
							'css'    => $objSpieler->freilos ? 'freilos' : '',
							'nummer' => $nummer,
							'name'   => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getSpielername($objSpieler),
							'titel'  => $objSpieler->titel,
							'land'   => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getLand($objSpieler->country),
							'verein' => $objSpieler->verein,
							'dwz'    => $objSpieler->dwz,
							'elo'    => $objSpieler->elo,
							'bild'   => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getFoto($objSpieler, $objTurnier->imageSize_Tabelle)
						);
					}
				}
				break;

			case 'ranking'    : // Rangliste

				$this->strTemplate = 'ce_schachturnier_rangliste';
				$this->Template = new \FrontendTemplate($this->strTemplate);

				// Ergebnisse bei den Spielern hinzufügen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Ergebnisse($objTurnier, $objSpieler, $this->schachturnier_runde);

				// Sonneborn-Berger-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::SonnebornBergerWertung($spieler);
				// Buchholz-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::BuchholzWertung($spieler);

				// Spieler sortieren nach gewünschter Wertungsreihenfolge
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Rangliste($spieler, unserialize($objTurnier->wertungen));

				// Auf- und Absteiger markieren
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::AufAbsteiger($spieler, $objTurnier->aufsteiger, $objTurnier->absteiger);

				// Ausgabedaten zusammenbauen
				$daten = $spieler;
				break;

			case 'cross_nr'   : // Kreuztabelle (nach Nummern)
				$spieler = self::getSpieler($objTurnier, $objSpieler);
				break;

			case 'cross_rang' : // Kreuztabelle (nach Rang)
				$this->strTemplate = 'ce_schachturnier_kreuztabelle';
				$this->Template = new \FrontendTemplate($this->strTemplate);

				$tabelle = new \Schachbulle\ContaoSchachturnierBundle\Classes\Tabelle($this->schachturnier);

				// Ergebnisse bei den Spielern hinzufügen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Ergebnisse($objTurnier, $objSpieler);

				// Sonneborn-Berger-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::SonnebornBergerWertung($spieler);
				// Buchholz-Wertung berechnen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::BuchholzWertung($spieler);

				// Spieler sortieren nach gewünschter Wertungsreihenfolge
				if($spieler)
				{
					$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Rangliste($spieler, unserialize($objTurnier->wertungen));
				}

				// Auf- und Absteiger markieren
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::AufAbsteiger($spieler, $objTurnier->aufsteiger, $objTurnier->absteiger);

				// Ergebnisse als Kreuztabelle eintragen
				$spieler = \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::Ergebnismatrix($spieler);

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

				$spieler = self::getSpieler($objTurnier, $objSpieler);
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
                    
						// Elo/DWZ einbauen
						$rating_weiss = '';
						if(in_array('elo', $view)) $rating_weiss .= '<span title="Elo">'.$spieler[$objResult->whiteName]['elo'].'</span> ';
						if(in_array('dwz', $view)) $rating_weiss .= '<span title="DWZ">'.$spieler[$objResult->whiteName]['dwz'].'</span> ';
						$rating_schwarz = '';
						if(in_array('elo', $view)) $rating_schwarz .= '<span title="Elo">'.$spieler[$objResult->blackName]['elo'].'</span> ';
						if(in_array('dwz', $view)) $rating_schwarz .= '<span title="DWZ">'.$spieler[$objResult->blackName]['dwz'].'</span> ';

						$paarung[$objResult->round][$objResult->board] = array
						(
							'css'            => $css,
							'weiss_id'       => $objResult->whiteName,
							'weiss_name'     => $weiss,
							'weiss_rating'   => $rating_weiss,
							'weiss_nummer'   => $spieler[$objResult->whiteName]['nummer'],
							'weiss_bild'     => $spieler[$objResult->whiteName]['bild'],
							'weiss_land'     => $spieler[$objResult->whiteName]['land'],
							'schwarz_id'     => $objResult->blackName,
							'schwarz_name'   => $schwarz,
							'schwarz_rating' => $rating_schwarz,
							'schwarz_nummer' => $spieler[$objResult->blackName]['nummer'],
							'schwarz_bild'   => $spieler[$objResult->blackName]['bild'],
							'schwarz_land'   => $spieler[$objResult->blackName]['land'],
							'datum'          => $objResult->datum ? date('d.m.Y', $objResult->datum) : '',
							'ergebnis'       => $objResult->result ? $objResult->result : '-',
							'info'           => $objResult->info,
						);
					}
					// Spielfrei ergänzen
					//$paarung = self::Spielfreisuche($paarung, $spieler);
				}

				// Bestimmte Runde soll ausgegeben werden: Paarungen modifizieren und überflüssige Runden entfernen
				if($this->schachturnier_runde != '')
				{
					foreach($paarung as $key => $value)
					{
						if($this->schachturnier_runde != $key) unset($paarung[$key]);
					}
				}

				// Ergebnisse sollen nicht ausgegeben werden
				if($this->schachturnier_noresults)
				{
					foreach($paarung as $runde => $value)
					{
						foreach($paarung[$runde] as $brett => $item)
						{
							$paarung[$runde][$brett]['ergebnis'] = '-';
						}
					}
				}

				//echo '<pre>';
				//print_r($paarung);
				//echo '</pre>';
				
				
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
		$this->Template->view_foto = in_array('foto', $view);
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

	function getSpieler($objTurnier, $objSpieler)
	{
		$spieler = array();
		if($objSpieler->numRows)
		{
			$nummer = 0;
			// Datensätze verarbeiten
			while($objSpieler->next())
			{
				$nummer++;
				$spieler[$objSpieler->id] = array
				(
					'css'           => $objSpieler->freilos ? 'freilos' : '',
					'nummer'        => $nummer,
					'name'          => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getSpielername($objSpieler),
					'vorname'       => $objSpieler->firstname,
					'nachname'      => $objSpieler->lastname,
					'titel'         => $objSpieler->titel,
					'land'          => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getLand($objSpieler->country),
					'verein'        => $objSpieler->verein,
					'ausgeschieden' => $objSpieler->ausgeschieden,
					'freilos'       => $objSpieler->freilos,
					'dwz'           => $objSpieler->dwz,
					'elo'           => $objSpieler->elo,
					'bild'          => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getFoto($objSpieler, $objTurnier->imageSize_Tabelle)
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
