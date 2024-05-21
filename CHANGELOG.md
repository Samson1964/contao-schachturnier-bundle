# Schachturnier-Bundle Changelog

## Version 2.0.0 (2024-05-21)

* Add: PHP8-Unterstützung

## Version 1.0.0 (2023-11-24)

* Add: Abhängigkeit codefog/contao-haste
* Change: Toogle-Funktion Haste eingebaut in allen DCA
* Add: Ausgabe des Feldes Herkunft (Meister, Pokalsieger usw.) in Backendliste Spieler
* Add: Ausgeschiedene Spieler in Paarungsliste Backend markieren
* Add: tl_schachturnier.fromDateView und tl_schachturnier.toDateView -> Turnierbeginn und Turnierende im Frontend unter Tabellen anzeigen
* Add: Paarungsgenerator -> Prüfung ob alle Startnummern vergeben wurden, Ausgabe einer Fehlermeldung wenn eine fehlt (Fehler war: Wird der Paarungsgenerator mit einer unbelegten Spielernummer aufgerufen, stürzt er ab)

## Version 0.3.4 (2023-07-24)

* Change: tl_schachturnier_partien -> In der Auflistung werden die Runden durch eine Linie getrennt, der besseren Übersichtlichkeit wegen
* Add: tl_schachturnier_partien -> Filter bei blackName und whiteName (leider nur mit den IDs der Spieler)
* Add: tl_schachturnier_partien -> Filter für Ergebnis
* Add: tl_schachturnier_partien -> Filter für Runde

## Version 0.3.3 (2023-07-21)

* Change: default.css verbessert

## Version 0.3.2 (2023-07-18)

* Fix: Kreuztabelle Ergebnisdarstellung, Gesamtpunktzahl
* Fix: Auf- und Absteiger wurden nicht korrekt angezeigt -> Funktion war nicht eingebaut, sondern mit Standardwerten befüllt

## Version 0.3.1 (2023-02-17)

* Fix: Klasse Tabelle -> Übergabe an Helper::sortArrayByFields, nur wenn Array gefüllt ist
* Fix: Inhaltselement Schachturnier -> Übergabe an Helper::Rangliste, nur wenn Array gefüllt ist

## Version 0.3.0 (2022-12-05)

* Add: tl_schachturnier_spieler.herkunft -> Markierung Meister, Pokalsieger, Neuling, Absteiger
* Add: Ausgabe der Herkunft in Ergebnislisten
* Add: tl_schachturnier_spieler.partienwertung -> Unterauswahl für die ausgeschieden-Spalte, um festzulegen, wie die Ergebnisse dargestellt werden sollen.
* Add: Klasse Tabelle für die Darstellung von Tabellen

## Version 0.2.2 (2022-06-02)

* Add: Auf- und Absteiger in einem Turnier markieren (in Turniereinstellungen und optional beim Spieler)
* Add: Zusatzinformationen in der Spielerliste - Status Ab- und Aufstieg

## Version 0.2.1 (2022-05-30)

* Fix: Sonneborn-Berger-Wertung wird falsch berechnet

## Version 0.2.0 (2022-05-29)

* Fix: Falsche Hintergrundfarbe für Kopfspalten
* Add: Ausgabe einer Kreuztabelle nach Rang

## Version 0.1.0 (2022-05-29)

* Change: Freilos in Teilnehmerliste und Paarungsliste im Frontend nicht mit grauem Hintergrund ausgeben
* Add: CSS für die Kopfzeile
* Add: CSS-Klasse für als abgesagt (E) markierte Spieler
* Add: tl_schachturnier.wertungen - Wertungsreihenfolge einstellen
* Add: Ausgabe einer Rangliste

## Version 0.0.5 (2022-05-06)

* Add: Spieler als Dummy-Eintrag markieren (Freilos, Spielfrei)
* Fix: In Paarungsliste spielfrei kennzeichnen
* Fix: Sortierung der Spieler im Backend korrigiert -> 1, 2, 3, ..., 10 statt 1, 10, 2, 3
* Fix: Alternatives Datum -> Blank anzeigen statt 01.01.1970
* Add: Paarungsliste im Backend -> Ergebnis ja/nein grün/rot markieren

## Version 0.0.4 (2022-05-02)

* Add: Absagen als MCW in Paarungen eingebaut
* Add: Frontendausgabe der abgesagten Partien in Paarungslisten
* Add: Spielfrei-Ausgabe in Paarungslisten

## Version 0.0.3 (2022-05-01)

* Fix: Datumsausgaben im Backend von JJJJTTMM geändert auf TT.MM.JJJJ
* Add: CSS für das Frontend
* Fix: Übersetzungen
* Add: Ausgeschieden-Checkbox bei den Spielern
* Fix: Reload im Inhaltselement deaktiviert
* Fix: Debug-Ausgaben im Template entfernt

## Version 0.0.2 (2022-04-30)

* Add: schachbulle/contao-helper-bundle und menatwork/contao-multicolumnwizard-bundle
* Add: Paarungsgenerator
* Add: Ausgabe Teilnehmerliste, Paarungsliste

## Version 0.0.1 (2022-03-07)

* Initiale Version

