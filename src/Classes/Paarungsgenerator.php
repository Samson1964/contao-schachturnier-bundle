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
		// Turnierdaten laden
		$objTurnier = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier WHERE id=?")
		                                      ->execute(\Input::get('id'));
		// Spielerliste des Turniers einlesen
		$objPlayer = \Database::getInstance()->prepare("SELECT * FROM tl_schachturnier_spieler WHERE pid=? ORDER BY nummer")
		                                     ->execute(\Input::get('id'));

		if($objTurnier->type == '' || $objTurnier->type == 'ko')
		{
			// Turniertyp leer oder K.o./Schweizer System -> dann beenden mit Fehlermeldung
			\Message::addError('Automatische Paarungen bei Turniertyp <b>'.$objTurnier->type.'</b> nicht erlaubt!');
			\System::setCookie('BE_PAGE_OFFSET', 0, 0);
			$request = str_replace('&key=pairs_generate', '', \Environment::get('request'));
			$this->redirect($request);
			return false;
		}
		
		$player = array();
		while($objPlayer->next())
		{
			$player[$objPlayer->nummer] = array
			(
				'id'   => $objPlayer->id,
				'name' => $objPlayer->surname .', '.$objPlayer->prename
			);
		}

		// Prüfen ob alle Startnummern vergeben wurden
		$fehlend = array();
		for($x = 1; $x <= count($player); $x++)
		{
			if(!isset($player[$x])) $fehlend[] = $x; // Nummer nicht vorhanden
		}
		if(count($fehlend))
		{
			\Message::addError('Paarungen nicht möglich. Startnummer '.implode(',', $fehlend).' fehlt.');
			\System::setCookie('BE_PAGE_OFFSET', 0, 0);
			$request = str_replace('&key=pairs_generate', '', \Environment::get('request'));
			$this->redirect($request);
			return false;
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
		
		if($objTurnier->type == 'dr')
		{
			// Doppelrundenturnier, deshalb Paarungen mit neuen Rundennummern wiederholen
			// Paarungen generieren und mit verkehrten Farben speichern
			for($runde = 1; $runde <= $maxround; $runde++)
			{
				$paarung = $this->Standardsystem(count($player), $runde);
				$brett = 0;
				foreach($paarung as $item)
				{
					$brett++;
					$set = array
					(
						'pid'       => \Input::get('id'),
						'tstamp'    => $zeit,
						'whiteName' => $player[$item['s']]['id'],
						'blackName' => $player[$item['w']]['id'],
						'round'     => $runde+$maxround,
						'board'     => $brett,
						'published' => 1
					);
					$objInsert = \Database::getInstance()->prepare("INSERT INTO tl_schachturnier_partien %s")
					                                     ->set($set)
					                                     ->execute();
				}
			}
		}
		
		//return "Fertig!";
		
		// Cookie setzen und Ergebnisseite aufrufen
		\Message::addConfirmation('Die Paarungen wurden neu erstellt.');
		\System::setCookie('BE_PAGE_OFFSET', 0, 0);
		$request = str_replace('&key=pairs_generate', '', \Environment::get('request'));
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
