<?php

namespace Schachbulle\ContaoSchachturnierBundle\Classes;

class Tabelle
{

	var $Tabelle = array();
	var $Kreuztabelle = array();
	var $TurnierID;

	public function __construct($id)
	{
		$this->TurnierID = $id; // Turnier-ID speichern
		self::LadeSpieler(); // Spielerdaten laden
		self::LadeErgebnisse(); // Ergebnisdaten laden
		//self::ZeigeTabelle(); // Tabelle verkürzt anzeigen
		self::SonnebornBerger(); // Sonneborn-Berger-Wertung berechnen
		self::LoescheFreilos(); // Freilos in der Tabelle löschen
		self::BerechneRangliste(); // Tabelle verkürzt anzeigen
		self::GeneriereKreuztabelle(); // Kreuztabelle erstellen
		self::AufAbsteigerMarkieren(2, 2);
		//echo "<pre>";
		//print_r($this->Kreuztabelle);
		//echo "</pre>";
		//echo '<pre>';
		//print_r($this->Tabelle);
		//echo '</pre>';
	}

	/***********************************************************************************************
	 * Funktion LadeSpieler
	 * ---------------------------------------------------------------------------------------------
	 * Lädt die Spielerdaten in das Tabelle-Array
	 * ---------------------------------------------------------------------------------------------
	 * @param -
	 * @retun array: Initialisiertes Tabelle-Array
	 * ---------------------------------------------------------------------------------------------
	 **********************************************************************************************/
	public function LadeSpieler()
	{
		// Spieler laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_spieler WHERE pid = ? AND published = ?')
		                                     ->execute($this->TurnierID, 1);

		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
			{
				// herkunft laden
				$herkunft = (array)unserialize($objResult->herkunft);
				if($herkunft[0] == '') $herkunft = array();

				// Name modifizieren
				// Titel, Vorname und Nachname zusammenfügen
				$name = $objResult->titel ? $objResult->titel.' '.$objResult->firstname.' '.$objResult->lastname : $objResult->firstname.' '.$objResult->lastname;
				// Herkunft hinzufügen
				$name = $herkunft[0] != '' ? $name.' ('.implode($herkunft, ',').')' : $name;
				// Ausgeschieden-Markierung
				$name = $objResult->ausgeschieden ? '<s>'.$name.'</s>' : $name;

				$this->Tabelle[$objResult->id] = array
				(
					'id'             => $objResult->id,
					'css'            => '',
					'nummer'         => $objResult->nummer,
					'name'           => $name,
					'vorname'        => $objResult->firstname,
					'nachname'       => $objResult->lastname,
					'titel'          => $objResult->titel,
					'land'           => $objResult->land,
					'verein'         => $objResult->verein,
					'ausgeschieden'  => $objResult->ausgeschieden,
					'partienwertung' => $objResult->partienwertung,
					'herkunft'       => $herkunft,
					'freilos'        => $objResult->freilos,
					'dwz'            => $objResult->dwz,
					'elo'            => $objResult->elo,
					'bild'           => $objResult->singleSRC,
					'unaufsteigbar'  => $objResult->unaufsteigbar,
					'unabsteigbar'   => $objResult->unabsteigbar,
					'aufsteiger'     => $objResult->aufsteiger,
					'absteiger'      => $objResult->absteiger,
					'spiele'         => 0,
					'2punkte'        => 0,
					'3punkte'        => 0,
					'sobe'           => 0,
					'buch'           => 0,
					'siege'          => 0,
					'partien'        => array(),
					'platz'          => 0
				);
			}
		}
	}


	/***********************************************************************************************
	 * Funktion LadeErgebnisse
	 * ---------------------------------------------------------------------------------------------
	 * Lädt die Ergebnisdaten in das Tabelle-Array
	 * ---------------------------------------------------------------------------------------------
	 * @param -
	 * @retun array: Modifiziertes Tabelle-Array
	 * ---------------------------------------------------------------------------------------------
	 **********************************************************************************************/
	public function LadeErgebnisse()
	{
		// Paarungen laden
		$objResult = \Database::getInstance()->prepare('SELECT * FROM tl_schachturnier_partien WHERE pid = ? AND published = ?')
		                                     ->execute($this->TurnierID, 1);
		if($objResult->numRows)
		{
			// Datensätze verarbeiten
			while($objResult->next())
			{
				if($this->Tabelle[$objResult->whiteName]['freilos'] || $this->Tabelle[$objResult->blackName]['freilos'])
				{
					// Einer der beiden Spieler ist das Freilos, Paarung nicht berücksichtigen
					continue;
				}
				else
				{
					// Reguläre Paarung -> auswerten
					switch($objResult->result)
					{
						case '1:0':
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(1)', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['2punkte'] += 1;
									$this->Tabelle[$objResult->whiteName]['3punkte'] += 3;
									$this->Tabelle[$objResult->whiteName]['siege'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => 1, 'anzeige' => '1', 'gegner' => $objResult->blackName);
								}
							}
							else
							{
								// Weiß-Spieler normal werten
								$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
								$this->Tabelle[$objResult->whiteName]['2punkte'] += 1;
								$this->Tabelle[$objResult->whiteName]['3punkte'] += 3;
								$this->Tabelle[$objResult->whiteName]['siege'] += 1;
								$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => 1, 'anzeige' => '1', 'gegner' => $objResult->blackName);
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(0)', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => 0, 'anzeige' => '0', 'gegner' => $objResult->whiteName);
								}
							}
							else
							{
								// Schwarz-Spieler normal werten
								$this->Tabelle[$objResult->blackName]['spiele'] += 1;
								$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => 0, 'anzeige' => '0', 'gegner' => $objResult->whiteName);
							}
							break;

						case '+:-':
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(+)', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['2punkte'] += 1;
									$this->Tabelle[$objResult->whiteName]['3punkte'] += 3;
									$this->Tabelle[$objResult->whiteName]['siege'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->blackName);
								}
							}
							else
							{
								// Weiß-Spieler normal werten
								$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
								$this->Tabelle[$objResult->whiteName]['2punkte'] += 1;
								$this->Tabelle[$objResult->whiteName]['3punkte'] += 3;
								$this->Tabelle[$objResult->whiteName]['siege'] += 1;
								$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->blackName);
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(-)', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->whiteName);
								}
							}
							else
							{
								// Schwarz-Spieler normal werten
								$this->Tabelle[$objResult->blackName]['spiele'] += 1;
								$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->whiteName);
							}
							break;

						case '0:1':
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(0)', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => 0, 'anzeige' => '0', 'gegner' => $objResult->blackName);
								}
							}
							else
							{
								// Weiß-Spieler normal werten
								$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
								$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => 0, 'anzeige' => '0', 'gegner' => $objResult->blackName);
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(1)', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['2punkte'] += 1;
									$this->Tabelle[$objResult->blackName]['3punkte'] += 3;
									$this->Tabelle[$objResult->blackName]['siege'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => 1, 'anzeige' => '1', 'gegner' => $objResult->whiteName);
								}
							}
							else
							{
								// Schwarz-Spieler normal werten
								$this->Tabelle[$objResult->blackName]['spiele'] += 1;
								$this->Tabelle[$objResult->blackName]['2punkte'] += 1;
								$this->Tabelle[$objResult->blackName]['3punkte'] += 3;
								$this->Tabelle[$objResult->blackName]['siege'] += 1;
								$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => 1, 'anzeige' => '1', 'gegner' => $objResult->whiteName);
							}
							break;

						case '-:+':
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(-)', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->blackName);
								}
							}
							else
							{
								// Weiß-Spieler normal werten
								$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
								$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->blackName);
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(+)', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['2punkte'] += 1;
									$this->Tabelle[$objResult->blackName]['3punkte'] += 3;
									$this->Tabelle[$objResult->blackName]['siege'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->whiteName);
								}
							}
							else
							{
								// Schwarz-Spieler normal werten
								$this->Tabelle[$objResult->blackName]['spiele'] += 1;
								$this->Tabelle[$objResult->blackName]['2punkte'] += 1;
								$this->Tabelle[$objResult->blackName]['3punkte'] += 3;
								$this->Tabelle[$objResult->blackName]['siege'] += 1;
								$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->whiteName);
							}
							break;

						case '½:½':
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(½)', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['2punkte'] += .5;
									$this->Tabelle[$objResult->whiteName]['3punkte'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => 0.5, 'anzeige' => '½', 'gegner' => $objResult->blackName);
								}
							}
							else
							{
								// Weiß-Spieler normal werten
								$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
								$this->Tabelle[$objResult->whiteName]['2punkte'] += .5;
								$this->Tabelle[$objResult->whiteName]['3punkte'] += 1;
								$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => 0.5, 'anzeige' => '½', 'gegner' => $objResult->blackName);
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(½)', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['2punkte'] += .5;
									$this->Tabelle[$objResult->blackName]['3punkte'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => 0.5, 'anzeige' => '½', 'gegner' => $objResult->whiteName);
								}
							}
							else
							{
								// Schwarz-Spieler normal werten
								$this->Tabelle[$objResult->blackName]['spiele'] += 1;
								$this->Tabelle[$objResult->blackName]['2punkte'] += .5;
								$this->Tabelle[$objResult->blackName]['3punkte'] += 1;
								$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => 0.5, 'anzeige' => '½', 'gegner' => $objResult->whiteName);
							}
							break;

						case '=:=':
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(=)', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['2punkte'] += .5;
									$this->Tabelle[$objResult->whiteName]['3punkte'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '=', 'anzeige' => '=', 'gegner' => $objResult->blackName);
								}
							}
							else
							{
								// Weiß-Spieler normal werten
								$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
								$this->Tabelle[$objResult->whiteName]['2punkte'] += .5;
								$this->Tabelle[$objResult->whiteName]['3punkte'] += 1;
								$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '=', 'anzeige' => '=', 'gegner' => $objResult->blackName);
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(=)', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['2punkte'] += .5;
									$this->Tabelle[$objResult->blackName]['3punkte'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '=', 'anzeige' => '=', 'gegner' => $objResult->whiteName);
								}
							}
							else
							{
								// Schwarz-Spieler normal werten
								$this->Tabelle[$objResult->blackName]['spiele'] += 1;
								$this->Tabelle[$objResult->blackName]['2punkte'] += .5;
								$this->Tabelle[$objResult->blackName]['3punkte'] += 1;
								$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '=', 'anzeige' => '=', 'gegner' => $objResult->whiteName);
							}
							break;

						case '-:-':
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(-)', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->blackName);
								}
							}
							else
							{
								// Weiß-Spieler normal werten
								$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
								$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->blackName);
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '(-)', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->whiteName);
								}
							}
							else
							{
								// Schwarz-Spieler normal werten
								$this->Tabelle[$objResult->blackName]['spiele'] += 1;
								$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->whiteName);
							}
							break;

						default: // Kein Ergebnis eingetragen
							// =======================================================
							// Partie beim Weiß-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->whiteName]['ausgeschieden'])
							{
								// Weiß-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->whiteName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten, also keine Punkte addieren
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->blackName);
									// Beim Gegner als kampflos gewonnen werten
									$this->Tabelle[$objResult->blackName]['2punkte'] += 1;
									$this->Tabelle[$objResult->blackName]['3punkte'] += 3;
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->whiteName);
								}
								elseif($this->Tabelle[$objResult->whiteName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->blackName);
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->whiteName);
								}
							}

							// =======================================================
							// Partie beim Schwarz-Spieler addieren
							// =======================================================
							if($this->Tabelle[$objResult->blackName]['ausgeschieden'])
							{
								// Schwarz-Spieler ist ausgeschieden
								if($this->Tabelle[$objResult->blackName]['partienwertung'] == 1)
								{
									// Kampflos verloren werten
									$this->Tabelle[$objResult->blackName]['spiele'] += 1;
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->whiteName);
									// Beim Gegner als kampflos gewonnen werten
									$this->Tabelle[$objResult->whiteName]['2punkte'] += 1;
									$this->Tabelle[$objResult->whiteName]['3punkte'] += 3;
									$this->Tabelle[$objResult->whiteName]['spiele'] += 1;
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->blackName);
								}
								elseif($this->Tabelle[$objResult->blackName]['partienwertung'] == 2)
								{
									// Wie gespielt werten
									$this->Tabelle[$objResult->blackName]['partien'][] = array('ergebnis' => '-', 'anzeige' => '-', 'gegner' => $objResult->whiteName);
									$this->Tabelle[$objResult->whiteName]['partien'][] = array('ergebnis' => '+', 'anzeige' => '+', 'gegner' => $objResult->blackName);
								}
							}
					}
				}
			}
		}

	}

	public function ZeigeTabelle()
	{
		// Tabelle der Spieler aufsteigend prüfen
		echo '<table>';
		echo '<tr>';
		echo '<td>ID</td>';
		echo '<td>Name</td>';
		echo '<td>Nummer</td>';
		echo '<td>Sp.</td>';
		echo '<td>Pkt.</td>';
		echo '<td>SoBe</td>';
		echo '<td>Partien</td>';
		echo '</tr>';
		foreach($this->Tabelle as $id => $arrSpieler)
		{
			echo '<tr>';
			echo '<td>'.$id.'</td>';
			echo '<td>'.$this->Tabelle[$id]['name'].'</td>';
			echo '<td>'.$this->Tabelle[$id]['nummer'].'</td>';
			echo '<td>'.$this->Tabelle[$id]['spiele'].'</td>';
			echo '<td>'.$this->Tabelle[$id]['2punkte'].'</td>';
			echo '<td>'.$this->Tabelle[$id]['sobe'].'</td>';
			echo '<td>';
			for($x = 0; $x < count($this->Tabelle[$id]['partien']); $x++)
			{
				echo 'E='.$this->Tabelle[$id]['partien'][$x]['ergebnis'];
				echo ' A='.$this->Tabelle[$id]['partien'][$x]['anzeige'];
				echo ' G='.$this->Tabelle[$id]['partien'][$x]['gegner'];
				echo ' | ';
			}
			echo '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	public function SonnebornBerger()
	{
		// Tabelle der Spieler aufsteigend prüfen
		foreach($this->Tabelle as $id => $arrSpieler)
		{
			// Partien-Array durchlaufen und Sonneborn-Berger-Wertung aufaddieren
			for($x = 0; $x < count($this->Tabelle[$id]['partien']); $x++)
			{
				$ergebnis = $this->Tabelle[$id]['partien'][$x]['ergebnis'];
				$gegner = $this->Tabelle[$id]['partien'][$x]['gegner'];
				$punkteGegner = $this->Tabelle[$gegner]['2punkte'];
				// Faktor ermitteln
				switch($ergebnis)
				{
					case '0':
					case '-':
						$faktor = 0;
						break;
					case '1':
					case '+':
						$faktor = 1;
						break;
					case '.5':
					case '=':
						$faktor = .5;
						break;
					default:
						$faktor = 0;
				}
				// Wertung hinzuaddieren
				//if($id == 12) echo "Erg=$ergebnis / gg.=$gegner / pktgg=$punkteGegner / f=$faktor<br>";
				$this->Tabelle[$id]['sobe'] += ($punkteGegner * $faktor);
			}
		}
	}

	/***********************
	 * Funktion Rangliste
	 * Sortiert das Spieler-Array nach gewünschter Wertungsreihenfolge
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public function BerechneRangliste()
	{

		$reihenfolge = array
		(
			'2punkte' => SORT_DESC,
			'sobe'    => SORT_DESC,
			'spiele'  => SORT_ASC,
		);

		// Sortieren, wenn Tabelle vorhanden
		if($this->Tabelle)
		{
			$this->Tabelle = \Schachbulle\ContaoHelperBundle\Classes\Helper::sortArrayByFields($this->Tabelle, $reihenfolge);
		}
		
		// Plazierung hinzufügen
		$platz = 1;
		foreach($this->Tabelle as $id => $arrSpieler)
		{
			$this->Tabelle[$id]['platz'] = $platz;
			$platz++;
		}

		// Index wiederherstellen
		$tabelleNeu = array();
		foreach($this->Tabelle as $id => $arrSpieler)
		{
			$tabelleNeu[$this->Tabelle[$id]['id']] = $this->Tabelle[$id];
		}
		$this->Tabelle = $tabelleNeu;
	}

	/***********************
	 * Funktion LoescheFreilos
	 */
	public function LoescheFreilos()
	{
		foreach($this->Tabelle as $id => $arrSpieler)
		{
			if($this->Tabelle[$id]['freilos'])
			{
				unset($this->Tabelle[$id]);
			}
		}
	}

	/***********************
	 * Funktion GeneriereKreuztabelle
	 * Erstellt den Inhalt von $this->Kreuztabelle aus $this->Tabelle
	 */
	public function GeneriereKreuztabelle()
	{
		$i = 1;
		foreach($this->Tabelle as $id => $arrSpieler)
		{
			// Spieler-Stammdaten kopieren
			$this->Kreuztabelle[$i] = array
			(
				'id'             => $id,
				'css'            => $this->Tabelle[$id]['css'],
				'nummer'         => $this->Tabelle[$id]['nummer'],
				'name'           => $this->Tabelle[$id]['name'],
				'land'           => $this->Tabelle[$id]['land'],
				'verein'         => $this->Tabelle[$id]['verein'],
				'dwz'            => $this->Tabelle[$id]['dwz'],
				'elo'            => $this->Tabelle[$id]['elo'],
				'bild'           => $this->Tabelle[$id]['bild'],
				'spiele'         => $this->Tabelle[$id]['spiele'],
				'2punkte'        => $this->Tabelle[$id]['2punkte'],
				'3punkte'        => $this->Tabelle[$id]['3punkte'],
				'sobe'           => $this->Tabelle[$id]['sobe'],
				'buch'           => $this->Tabelle[$id]['buch'],
				'siege'          => $this->Tabelle[$id]['siege'],
				'platz'          => $this->Tabelle[$id]['platz'],
				'aufsteiger'     => $this->Tabelle[$id]['aufsteiger'],
				'absteiger'      => $this->Tabelle[$id]['absteiger'],
				'unaufsteigbar'  => $this->Tabelle[$id]['unaufsteigbar'],
				'unabsteigbar'   => $this->Tabelle[$id]['unabsteigbar'],
				'partien'        => array()
			);

			//print_r($this->Tabelle);
			// Ergebnisse übernehmen
			if(is_array($this->Tabelle[$id]['partien']))
			{
				for($x = 0; $x < count($this->Tabelle[$id]['partien']); $x++)
				{
					$ergebnis = $this->Tabelle[$id]['partien'][$x]['anzeige'];
					$gegnerID = $this->Tabelle[$id]['partien'][$x]['gegner'];
					$gegnerNummer = $this->Tabelle[$gegnerID]['platz'];
					$this->Kreuztabelle[$i]['partien'][$gegnerNummer] = $ergebnis;
				}
			}

			$i++;
		}
	}

	public function getTabelle()
	{
		return $this->Kreuztabelle;
	}


	/***********************
	 * Funktion AufAbsteigerMarkieren
	 * Markiert die Auf- und Absteiger im Spieler-Array, d.h. beim Feld css wird die Klasse aufsteiger oder absteiger hinzugefügt
	 * @param array: Spieler-Array
	 * @retun array: Modifiziertes Spieler-Array
	 */
	public function AufAbsteigerMarkieren($aufsteiger = 0, $absteiger = 0)
	{

		// Aufsteiger markieren, Spieler-Array von vorn nach hinten durchlaufen
		$index = 1;
		foreach($this->Kreuztabelle as $id => $arrSpieler)
		{
			// Spieler wurde als Aufsteiger festgelegt (zählt bei normalen Aufsteigern mit)
			if($this->Kreuztabelle[$id]['aufsteiger'])
			{
				$this->Kreuztabelle[$id]['css'] .= 'aufsteiger ';
			}
        
			// Aufsteigerplatz gefunden, falls innerhalb der Berechtigten und nicht unaufsteigbar
			if($index <= $aufsteiger && !$this->Kreuztabelle[$id]['unaufsteigbar'])
			{
				$this->Kreuztabelle[$id]['css'] .= 'aufsteiger ';
				$index++;
			}
			elseif($this->Kreuztabelle[$id]['unaufsteigbar'])
			{
			}
			else
			{
				$index++;
			}
        
		}
		
		// Absteiger markieren, Spieler-Array von hinten nach vorn durchlaufen
		$index = 1;
		foreach(array_reverse($this->Kreuztabelle, true) as $id => $arrSpieler)
		{
			// Spieler wurde als Absteiger festgelegt (zählt bei normalen Absteigern mit)
			if($this->Kreuztabelle[$id]['absteiger'])
			{
				$this->Kreuztabelle[$id]['css'] .= 'absteiger ';
			}
        
			// Aufsteigerplatz gefunden, falls innerhalb der Berechtigten und nicht unaufsteigbar
			if($index <= $absteiger && !$this->Kreuztabelle[$id]['unabsteigbar'])
			{
				$this->Kreuztabelle[$id]['css'] .= 'absteiger ';
				$index++;
			}
			elseif($this->Kreuztabelle[$id]['unabsteigbar'])
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

	}

}
