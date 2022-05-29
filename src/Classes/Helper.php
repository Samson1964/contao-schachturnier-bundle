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
	public function SonnebornBergerWertung($array)
	{
		foreach($array as $id => $arrSpieler)
		{
			if($arrSpieler['partien'])
			{
				foreach($arrSpieler['partien'] as $game)
				{
					$punkteGegner = $array[$game['gegner']]['2punkte'] * $game['ergebnis'];
					$array[$id]['sobe'] += $punkteGegner;
				}
			}
		}
		return $array;
	}

	/***********************
	 * Funktion BuchholzWertung
	 * Ergänzt in einem Spieler-Array die Buchholz-Wertung
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public function BuchholzWertung($array)
	{
		foreach($array as $id => $arrSpieler)
		{
			if($arrSpieler['partien'])
			{
				foreach($arrSpieler['partien'] as $game)
				{
					$punkteGegner = $array[$game['gegner']]['2punkte'];
					$array[$id]['buch'] += $punkteGegner;
				}
			}
		}
		return $array;
	}

	/***********************
	 * Funktion Rangliste
	 * Sortiert das Spieler-Array nach gewünschter Wertungsreihenfolge
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public function Rangliste($array, $wertung)
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
		$array = \Schachbulle\ContaoHelperBundle\Classes\Helper::sortArrayByFields($array, $reihenfolge);

		// Plazierung hinzufügen
		$platz = 1;
		foreach($array as $id => $arrSpieler)
		{
			$array[$id]['platz'] = $platz;
			$platz++;
		}

		return $array;
	}

}
