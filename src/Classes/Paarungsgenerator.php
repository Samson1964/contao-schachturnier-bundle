<?php

namespace Schachbulle\ContaoSchachturnierBundle\Classes;

class Paarungsgenerator extends \Frontend
{

	/***********************
	 * Generiert die Paarungen
	 */
	public function generatePairs()
	{

		$zeit = time();
		// Spielerliste des Turniers einlesen
		$objPlayer = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_spieler WHERE pid=? ORDER BY nummer")
		                                     ->execute(\Input::get('id'));

		$player = array();
		while ($objPlayer->next())
		{
			$player[$objPlayer->nummer] = array
			(
				'id'   => $objPlayer->id,
				'name' => $objPlayer->surname .', '.$objPlayer->prename
			);
		}

		// Alte Paarungen löschen
		$objPlayer = \Database::getInstance()->prepare("DELETE FROM tl_schachturnier_partien WHERE pid=?")
		                                     ->execute(\Input::get('id'));
		
		// Maximale Rundenanzahl feststellen
		if(count($player) % 2 != 0)
		{
			// Ungerade Spielerzahl
			$maxround = count($player);
		}
		else $maxround = count($player)-1;
		
		// Paarungen generieren und speichern
		for($runde = 1; $runde <= $maxround; $runde++)
		{
			$paarung = $this->Standardsystem(count($player), $runde);
			// Paarungen schreiben
			//echo "<pre>";
			//print_r($paarung);
			//echo "</pre>";
			$brett = 0;
			foreach($paarung as $item)
			{
				$brett++;
				$set = array
				(
					'pid'       => \Input::get('id'),
					'tstamp'    => $zeit,
					'whiteName' => $player[$item['w']]['id'],
					'blackName' => $player[$item['s']]['id'],
					'round'     => $runde,
					'board'     => $brett,
					'published' => 1
				);
				$objInsert = \Database::getInstance()->prepare("INSERT INTO tl_schachturnier_partien %s")
				                                     ->set($set)
				                                     ->execute();
			}
		}
		
		//return "Fertig!";
		
		// Cookie setzen und Ergebnisseite aufrufen
		\System::setCookie('BE_PAGE_OFFSET', 0, 0);
		$request = \Environment::get('request');
		//// In Request Tabellenverweis austauschen
		//$request = str_replace('&table=tl_schachtabelle', '&table=tl_chesstournament_results', $request);
		// In Request generatePairs-Befehl entfernen
		$request = str_replace('&key=pairs_generate', '', $request);
		$this->redirect($request);
	}
	
	/***********************
	 * liefert die Paarungen nach Standardsystem
	 * @param dim: Spieleranzahl
	 * @param runde: aktuelle Runde
	 */
	protected function Standardsystem($dim, $runde)
	{
		// Spieleranzahl begradigen
		if($dim % 2 != 0)
		{
			$dim++;
			$spielfrei = $dim;
		}
		else $spielfrei = false;
		
		$gegner = range(1, $dim);
		if($runde % 2){
			$gegner[0] = $odd = ($runde+1)/2;
			$gegner[1] = $dim;
		}
		else{
			$gegner[0] = $dim;
			$gegner[1] = $odd = ($runde+$dim)/2;
		}
		for ($i=2; $i< $dim;){
			$gegner[$i++]= ($odd++)%($dim-1)+1;
			$gegner[$i++]= ($runde-$odd+$dim-1)%($dim-1)+1;
		}

		$i = 0;
		for($x = 0; $x < $dim-1; $x+=2)
		{
			if($spielfrei && ($gegner[$x] == $dim || $gegner[$x+1] == $dim))
			{
				// Spielfrei erwischt
			}
			else
			{
				$paarung[$i]['w'] = $gegner[$x];
				$paarung[$i]['s'] = $gegner[$x+1];
				$i++;
			}
		}
		
		return $paarung;
	}
	
}
