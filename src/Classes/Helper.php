<?php

namespace Schachbulle\ContaoSchachturnierBundle\Classes;

class Helper
{

	/***********************
	 * Funktion SonnebornBergerWertung
	 * Ergänzt in einem Spieler-Array die Sonneborn-Berger-Wertung
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public static function SonnebornBergerWertung($spieler)
	{
		foreach($spieler as $id => $arrSpieler)
		{
			if($arrSpieler['partien'])
			{
				foreach($arrSpieler['partien'] as $game)
				{
					switch($game['ergebnis'])
					{
						case '-':
							$ergebnis = 0; break;
						case '=':
							$ergebnis = 0.5; break;
						case '+': // Darf nicht an erster Stelle stehen, weil 0 als + interpretiert wird
							$ergebnis = 1; break;
						default:
							$ergebnis = $game['ergebnis'];
					}

					$punkteGegner = $spieler['id'.$game['gegner']]['2punkte'] * $ergebnis;
					$spieler[$id]['sobe'] += $punkteGegner;
				}
			}
		}
		return $spieler;
	}

	/***********************
	 * Funktion BuchholzWertung
	 * Ergänzt in einem Spieler-Array die Buchholz-Wertung
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public static function BuchholzWertung($spieler)
	{
		foreach($spieler as $id => $arrSpieler)
		{
			if($arrSpieler['partien'])
			{
				foreach($arrSpieler['partien'] as $game)
				{
					$punkteGegner = $spieler['id'.$game['gegner']]['2punkte'];
					$spieler[$id]['buch'] += $punkteGegner;
				}
			}
		}
		return $spieler;
	}

	/***********************
	 * Funktion Rangliste
	 * Sortiert das Spieler-Array nach gewünschter Wertungsreihenfolge
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public static function Rangliste($spieler, $wertung)
	{
		$reihenfolge = array();
		if($wertung)
		{
			foreach($wertung as $kriterium)
			{
				$reihenfolge[$kriterium] = SORT_DESC;
			}
		}
		else
		{
			$reihenfolge['2punkte'] = SORT_DESC;
		}
		// Zusätzliches Kriterium
		$reihenfolge['spiele'] = SORT_ASC;

		// Sortieren
		$spieler = \Schachbulle\ContaoHelperBundle\Classes\Helper::sortArrayByFields($spieler, $reihenfolge);

		// Plazierung hinzufügen
		$platz = 1;
		foreach($spieler as $id => $arrSpieler)
		{
			$spieler[$id]['platz'] = $platz;
			$platz++;
		}

		return $spieler;
	}

	/***********************
	 * Funktion AufAbsteiger
	 * Markiert die Auf- und Absteiger im Spieler-Array, d.h. beim Feld css wird die Klasse aufsteiger oder absteiger hinzugefügt
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public static function AufAbsteiger($spieler, $aufsteiger = 0, $absteiger = 0)
	{

		// Aufsteiger markieren, Spieler-Array von vorn nach hinten durchlaufen
		$index = 1;
		foreach($spieler as $id => $arrSpieler)
		{
			// Spieler wurde als Aufsteiger festgelegt (zählt bei normalen Aufsteigern mit)
			if($spieler[$id]['aufsteiger'])
			{
				$spieler[$id]['css'] .= 'aufsteiger ';
			}

			// Aufsteigerplatz gefunden, falls innerhalb der Berechtigten und nicht unaufsteigbar
			if($index <= $aufsteiger && !$spieler[$id]['unaufsteigbar'])
			{
				$spieler[$id]['css'] .= 'aufsteiger ';
				$index++;
			}
			elseif($spieler[$id]['unaufsteigbar'])
			{
			}
			else
			{
				$index++;
			}

		}


		// Absteiger markieren, Spieler-Array von hinten nach vorn durchlaufen
		$index = 1;
		foreach(array_reverse($spieler) as $id => $arrSpieler)
		{
			// Spieler wurde als Absteiger festgelegt (zählt bei normalen Absteigern mit)
			if($spieler[$id]['absteiger'])
			{
				$spieler[$id]['css'] .= 'absteiger ';
			}

			// Aufsteigerplatz gefunden, falls innerhalb der Berechtigten und nicht unaufsteigbar
			if($index <= $absteiger && !$spieler[$id]['unabsteigbar'])
			{
				$spieler[$id]['css'] .= 'absteiger ';
				$index++;
			}
			elseif($spieler[$id]['unabsteigbar'])
			{
			}
			else
			{
				$index++;
			}

		}

		//echo "<pre>";
		//print_r(array_reverse($spieler));
		//echo "</pre>";

		return $spieler;
	}

	/***********************
	 * Funktion Ergebnisse
	 * xxx
	 * @param object: Turnier- und Spieler-Objekt
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public static function Ergebnisse($objTurnier, $objSpieler, $Runde = '')
	{
		$spieler = array();
		
		if($objSpieler->numRows)
		{
			// Datensätze verarbeiten
			while($objSpieler->next())
			{
				if(!$objSpieler->freilos)
				{
					$spieler['id'.$objSpieler->id] = array
					(
						'css'           => '',
						'nummer'        => $objSpieler->nummer,
						'name'          => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getSpielername($objSpieler),
						'titel'         => $objSpieler->titel,
						'land'          => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getLand($objSpieler->country),
						'verein'        => $objSpieler->verein,
						'dwz'           => $objSpieler->dwz,
						'elo'           => $objSpieler->elo,
						'bild'          => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getFoto($objSpieler, $objTurnier->imageSize_Tabelle),
						'unaufsteigbar' => $objSpieler->unaufsteigbar,
						'unabsteigbar'  => $objSpieler->unabsteigbar,
						'aufsteiger'    => $objSpieler->aufsteiger,
						'absteiger'     => $objSpieler->absteiger,
						'spiele'        => 0,
						'2punkte'       => 0,
						'3punkte'       => 0,
						'sobe'          => 0,
						'buch'          => 0,
						'siege'         => 0,
						'partien'       => array(),
						'platz'         => 0
					);
				}
			}
		}

		// Paarungen laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_partien WHERE pid = ? AND published = ?')
		                                     ->execute($objTurnier->id, 1);
		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
			{
				if(($Runde != '' && $objResult->round <= $Runde) || $Runde == '')
				{
					switch($objResult->result)
					{
						case '1:0':
							$spieler['id'.$objResult->whiteName]['spiele'] += 1;
							$spieler['id'.$objResult->whiteName]['2punkte'] += 1;
							$spieler['id'.$objResult->whiteName]['3punkte'] += 3;
							$spieler['id'.$objResult->whiteName]['siege'] += 1;
							$spieler['id'.$objResult->whiteName]['partien'][] = array('ergebnis' => 1, 'gegner' => $objResult->blackName);
							$spieler['id'.$objResult->blackName]['spiele'] += 1;
							$spieler['id'.$objResult->blackName]['partien'][] = array('ergebnis' => 0, 'gegner' => $objResult->whiteName);
							break;
						case '+:-':
							$spieler['id'.$objResult->whiteName]['spiele'] += 1;
							$spieler['id'.$objResult->whiteName]['2punkte'] += 1;
							$spieler['id'.$objResult->whiteName]['3punkte'] += 3;
							$spieler['id'.$objResult->whiteName]['siege'] += 1;
							$spieler['id'.$objResult->whiteName]['partien'][] = array('ergebnis' => '+', 'gegner' => $objResult->blackName);
							$spieler['id'.$objResult->blackName]['spiele'] += 1;
							$spieler['id'.$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'gegner' => $objResult->whiteName);
							break;
						case '0:1':
							$spieler['id'.$objResult->whiteName]['spiele'] += 1;
							$spieler['id'.$objResult->whiteName]['partien'][] = array('ergebnis' => 0, 'gegner' => $objResult->blackName);
							$spieler['id'.$objResult->blackName]['spiele'] += 1;
							$spieler['id'.$objResult->blackName]['2punkte'] += 1;
							$spieler['id'.$objResult->blackName]['3punkte'] += 3;
							$spieler['id'.$objResult->blackName]['siege'] += 1;
							$spieler['id'.$objResult->blackName]['partien'][] = array('ergebnis' => 1, 'gegner' => $objResult->whiteName);
							break;
						case '-:+':
							$spieler['id'.$objResult->whiteName]['spiele'] += 1;
							$spieler['id'.$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'gegner' => $objResult->blackName);
							$spieler['id'.$objResult->blackName]['spiele'] += 1;
							$spieler['id'.$objResult->blackName]['2punkte'] += 1;
							$spieler['id'.$objResult->blackName]['3punkte'] += 3;
							$spieler['id'.$objResult->blackName]['siege'] += 1;
							$spieler['id'.$objResult->blackName]['partien'][] = array('ergebnis' => '+', 'gegner' => $objResult->whiteName);
							break;
						case '½:½':
							$spieler['id'.$objResult->whiteName]['spiele'] += 1;
							$spieler['id'.$objResult->whiteName]['2punkte'] += .5;
							$spieler['id'.$objResult->whiteName]['3punkte'] += 1;
							$spieler['id'.$objResult->whiteName]['partien'][] = array('ergebnis' => 0.5, 'gegner' => $objResult->blackName);
							$spieler['id'.$objResult->blackName]['spiele'] += 1;
							$spieler['id'.$objResult->blackName]['2punkte'] += .5;
							$spieler['id'.$objResult->blackName]['3punkte'] += 1;
							$spieler['id'.$objResult->blackName]['partien'][] = array('ergebnis' => 0.5, 'gegner' => $objResult->whiteName);
							break;
						case '=:=':
							$spieler['id'.$objResult->whiteName]['spiele'] += 1;
							$spieler['id'.$objResult->whiteName]['2punkte'] += .5;
							$spieler['id'.$objResult->whiteName]['3punkte'] += 1;
							$spieler['id'.$objResult->whiteName]['partien'][] = array('ergebnis' => '=', 'gegner' => $objResult->blackName);
							$spieler['id'.$objResult->blackName]['spiele'] += 1;
							$spieler['id'.$objResult->blackName]['2punkte'] += .5;
							$spieler['id'.$objResult->blackName]['3punkte'] += 1;
							$spieler['id'.$objResult->blackName]['partien'][] = array('ergebnis' => '=', 'gegner' => $objResult->whiteName);
							break;
						case '-:-':
							$spieler['id'.$objResult->whiteName]['spiele'] += 1;
							$spieler['id'.$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'gegner' => $objResult->blackName);
							$spieler['id'.$objResult->blackName]['spiele'] += 1;
							$spieler['id'.$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'gegner' => $objResult->whiteName);
							break;
						default:
					}
				}
			}
		}

		return $spieler;
	}

	/***********************
	 * Funktion Ergebnismatrix
	 * xxx
	 * @param array: Spieler-Array
	 * @return array: Modifiziertes Spieler-Array
	 */
	public static function Ergebnismatrix($spieler)
	{
		// Anzahl der Spieler
		$anzahlSpieler = count($spieler);

		// Ergebnismatrix erstellen
		$matrix = array();
		for($x = 0; $x <= $anzahlSpieler; $x++)
		{
			for($y = 0; $y <= $anzahlSpieler; $y++)
			{
				if($x == $y) $matrix[$x][$y] = 'x';
				else $matrix[$x][$y] = '';
			}
		}

		// Ergebnismatrix und Matrixindex in Spieler-Array übernehmen
		$index = 1;
		foreach($spieler as $id => $arrSpieler)
		{
			$spieler[$id]['matrix_index'] = $index;
			$spieler[$id]['matrix'] = $matrix;
			$index++;
		}

		// Ergebnisse in Matrix übertragen
		foreach($spieler as $id => $arrSpieler)
		{
			if($arrSpieler['partien'])
			{
				foreach($arrSpieler['partien'] as $game)
				{
					$matrixIndexX = $arrSpieler['matrix_index']; // "Ich" in Zeile
					$matrixIndexY = $spieler['id'.$game['gegner']]['matrix_index']; // Gegner in Spalte
					$spieler[$id]['matrix'][$matrixIndexX][$matrixIndexY] = $game['ergebnis'];
				}
			}
		}

		return $spieler;
	}

	/***********************
	 * Funktion getFoto
	 * Gibt das HTML für ein Bild zurück
	 * @param objSpieler (object): Spieler-Objekt
	 * @param bildgroesse (string): Format des Bildes
	 * @return string: HTML für das Bild
	 */
	public static function getFoto($objSpieler, $bildgroesse = false)
	{
		if($objSpieler->addImage)
		{
			if($objSpieler->singleSRC)
			{
				$bildgroesse = (array)unserialize($bildgroesse);
				$objFile = \FilesModel::findByPk($objSpieler->singleSRC);
				$objBild = new \stdClass();
				\Controller::addImageToTemplate($objBild, array('singleSRC' => $objFile->path, 'size' => $bildgroesse), \Config::get('maxImageWidth'), null, $objFile);
				//print_r($bildgroesse).'<br>';
				//echo $objBild->singleSRC.'<br>';
				//echo $objBild->src.'<br>';
				//echo $objBild->imgSize.'<br>';
				//echo $objBild->alt.'<br>';
				//echo $objBild->caption.'<br><br>';
				return '<a href="'.$objBild->singleSRC.'" data-lightbox="lightbox"><img src="'.$objBild->src.'" '.$objBild->imgSize.'></a>';
			}
		}
		return false;
	}

	/***********************
	 * Funktion getSpielername
	 * Gibt den Spielernamen anhand des Spieler-Objektes zurück
	 * @param objSpieler (object): Spieler-Objekt
	 * @return string: Spielername in der Form "Titel Vorname Nachname"
	 */
	public static function getSpielername($objSpieler)
	{
		// Name generieren
		if($objSpieler->freilos) $name = $objSpieler->lastname;
		else $name = $objSpieler->titel ? $objSpieler->titel.' '.$objSpieler->firstname.' '.$objSpieler->lastname : $objSpieler->firstname.' '.$objSpieler->lastname;

		// Herkunft (Neuling, Absteiger usw.) hinzufügen
		$herkunft = (array)unserialize($objSpieler->herkunft);
		if(count($herkunft) > 0 && $herkunft[0] != '') $name = $name.' ('.implode(',', $herkunft).')';
		
		return $name;
	}

	/***********************
	 * Funktion getLand
	 * Gibt den HTML-Code für das Land anhand des Spieler-Objektes zurück
	 * @param objSpieler (object): Spieler-Objekt
	 * @return string: Land als HTML
	 */
	public static function getLand($country)
	{
		$laender = \System::getCountries();
		$land = $country ? '<span class="flag-icon flag-icon-'.$country.'" title="'.$laender[$country].'"></span>' : '';
		return $land;
	}

}
