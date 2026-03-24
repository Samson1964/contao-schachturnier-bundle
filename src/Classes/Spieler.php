<?php

namespace Schachbulle\ContaoSchachturnierBundle\Classes;

class Spieler
{

	public $Spieler;

	/************************************************
	 * Funktion Konstruktur
	 * Initialisiert die Klasse mit den Spielern des Turniers
	 * @param integer: Turnier-ID
	 */
	public function __construct($turnierID)
	{
		// Spieler-Objekt laden
		$objSpieler = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? AND published = ? ORDER BY nummer ASC')
		                                      ->execute($turnierID, 1);

		if($objSpieler->numRows)
		{
			$nummer = 0;
			// Datensätze einlesen
			while($objSpieler->next())
			{
				$nummer++;
				$this->Spieler[] = array
				(
					// Rohdaten
					'vorname'         => $objSpieler->firstname,
					'nachname'        => $objSpieler->lastname,
					'nummer'          => $objSpieler->nummer,
					'dwz'             => $objSpieler->dwz,
					'elo'             => $objSpieler->elo,
					'titel'           => $objSpieler->titel,
					'land'            => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getLand($objSpieler->country),
					'verein'          => $objSpieler->verein,
					'ausgeschieden'   => $objSpieler->ausgeschieden,
					'partienwertung'  => $objSpieler->partienwertung,
					'freilos'         => $objSpieler->freilos,
					'herkunft'        => $objSpieler->herkunft,
					'unaufsteigbar'   => $objSpieler->unaufsteigbar,
					'unabsteigbar'    => $objSpieler->unabsteigbar,
					'aufsteiger'      => $objSpieler->aufsteiger,
					'absteiger'       => $objSpieler->absteiger,
					'fotoRAW'         => $objSpieler->addImage ? $objSpieler->singleSRC : false,
					'info'            => $objSpieler->info,
					// Zusätzliche Daten, teilweise konvertiert
					'nr'              => $nummer,
					'css'             => $objSpieler->freilos ? 'freilos' : '',
					'spielername'     => \Schachbulle\ContaoSchachturnierBundle\Classes\Helper::getSpielername($objSpieler),
					// Werte werden später ermittelt
					'foto'            => '', // aus fotoRAW
					'spiele'          => 0, // Werte für Ranglistenermittlung
					'2punkte'         => 0, // Werte für Ranglistenermittlung
					'3punkte'         => 0, // Werte für Ranglistenermittlung
					'sobe'            => 0, // Werte für Ranglistenermittlung
					'buch'            => 0, // Werte für Ranglistenermittlung
					'siege'           => 0, // Werte für Ranglistenermittlung
					'platz'           => 0, // Werte für Ranglistenermittlung
					'partien'         => array(), // enthält später die Ergebnisse
				);
			}
		}

	}
}
