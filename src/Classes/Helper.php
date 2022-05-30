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
	public function SonnebornBergerWertung($spieler)
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
	public function BuchholzWertung($spieler)
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
	public function Rangliste($spieler, $wertung)
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
	 * Funktion SpielerErgebnisse
	 * xxx
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public function SpielerErgebnisse($turnierId)
	{
		// Spieler laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? ORDER BY nummer ASC')
		                                     ->execute($turnierId);
		$spieler = array();
		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
			{
				// Name generieren
				if($objResult->freilos) $name = $objResult->lastname;
				else $name = $objResult->titel ? $objResult->titel.' '.$objResult->firstname.' '.$objResult->lastname : $objResult->firstname.' '.$objResult->lastname;
				
				if(!$objResult->freilos)
				{
					$spieler['id'.$objResult->id] = array
					(
						'nummer'  => $objResult->nummer,
						'name'    => $name,
						'titel'   => $objResult->titel,
						'land'    => $objResult->land,
						'verein'  => $objResult->verein,
						'dwz'     => $objResult->dwz,
						'elo'     => $objResult->elo,
						'bild'    => $objResult->singleSRC,
						'spiele'  => 0,
						'2punkte' => 0,
						'3punkte' => 0,
						'sobe'    => 0,
						'buch'    => 0,
						'siege'   => 0,
						'partien' => array(),
						'platz'   => 0
					);
				}
			}
		}

		// Paarungen laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_partien WHERE pid = ? AND published = ?')
		                                     ->execute($turnierId, 1);
		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
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
	
		return $spieler;
	}

	/***********************
	 * Funktion Ergebnismatrix
	 * xxx
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public function Ergebnismatrix($spieler)
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
}
