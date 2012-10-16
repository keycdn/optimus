=== Optimus ===
Contributors: sergej.mueller
Tags: images, optimize, compress
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RDDW9FEHGLG6
Requires at least: 3.4
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Effektive Komprimierung der Bilder während des Upload-Vorgangs. Automatisch und ohne Qualitätsverlust.



== Description ==

*Optimus* übernimmt die automatische Komprimierung der hochgeladenen Medien. Die Dateigröße reduziert sich um bis zu 50 Prozent, die Qualität des Bildes bleibt garantiert erhalten. Praktisch und effektiv.


= Wichtig =
* Liest den Beschreibungstext vollständig durch
* Beachtet die Hinweise unter [FAQ](http://wordpress.org/extend/plugins/optimus/faq/)
* Unterstützt des Projekt (*warum?* weiter unten)


= Prinzip =
Während der Übertragung der Bilder in die Mediathek schickt das *Optimus*-Plugin die URL des Bildes an den entfernten *Optimus*-Server, der das Bild einliest, bearbeitet und in optimierter Form zurückgibt. Daraufhin speichert das *Optimus*-Plugin die komprimierte Ausgabe in der Mediathek.

Die Optimierung der Bilder (samt Thumbnails) erfolgt im Hintergrund und ist für Nutzer optisch nicht wahrzunehmen. Den Kompressionsgewinn pro Datei stellt *Optimus* innerhalb der Mediathek in Form eines Prozentwertes dar, siehe [Screenshot](http://wordpress.org/extend/plugins/optimus/screenshots/). An der gleichen Stelle werden auch Fehlercodes abgebildet.


= Vorteile =
* Verkleinerung der Dateigröße ohne Verlust der Qualität
* Optimierung der Vorschauvarianten eines Bildes
* Keine Einstellungen oder Code-Anpassungen notwendig
* Vorteilhafterer PageSpeed als Ranking-Faktor
* Geringere Ladezeit der Blogseiten
* Kostenlos und werbefrei


= Bonustipps =
* Fotos stets als JPEGs statt PNGs speichern und in den Blog übertragen. PNG ist sinnvoll bei Illustrationen, JPEG ist das richtige Format für Fotoaufnahmen. Weiterer Grund: Die Reduzierung der PNGs in der Größe dauert deutlich länger.
* Zahlreiche Blogger optimieren ihre Bilder händisch vor dem Upload mit Desktop-Tools wie [ImageOptim](http://playground.ebiene.de/png-bilder-optimieren/). *Optimus* hat den gravierenden Vorteil, dass von WordPress erstellte (und im Theme verknüpfte) Thumbnails eines Bildes ebenfalls behandelt und minimiert werden. Schliesslich werden im Theme fast immer Thumbnails (= Vorschaubilder) eingebunden und nur selten das Originalbild.


= Einschränkungen =
* Zugelassen sind Anfragen aus DACH. Benötigst einen Zugang außerhalb? Melde dich.
* Bilder in JPEG- und PNG-Formaten unterliegen der Dateigrößenreduzierung.
* Keep it simple: *Optimus* kommt vorerst ohne Optionsseite aus.
* Bilder über 300 KB werden ignoriert, Vorschauvarianten dennoch optimiert.


= Datenschutz =
* Nach der Optimierung und Auslieferung der optimierten Bilder löscht der *Optimus*-Server die temporär abgelegten Dateien - unverzüglich. Keine Aufbewahrung!
* Während der Kommunikation zum *Optimus*-Server teilt das Plugin die aktuelle Blog-URL mit. Auf diese Weise soll der Missbrauch der Software erkannt und Power-Nutzer identifiziert werden.
* Der Server-Standort ist Deutschland beim Hoster domainFACTORY.


= Warum mache ich das? =
Das *Smush.it Plugin* hat über Jahre hinweg einen perfekten Dienst geleistet. In letzter Zeit hat die Reaktionszeit des Dienstes sehr nachgelassen, so dass selten ein Bild zuverlässig optimiert wurde. Mit *Optimus* stelle ich eine geschwinde Alternative zur Verfügung, die jedoch nicht wie *Smush.it* enden soll. Daher meine Bitte: Missbraucht den Service nicht. Ich gebe euch ein feines und unkompliziertes Plugin in die Hände und erwarte Respekt und Kooperation.

= Finanzierung =
Zurzeit finanziere ich das Projekt aus Einnahmen meines [SEO-Plugins](http://wpseo.de). Performance-Server kosten Geld. Über die Unterstützung jeder Art freue ich mich dennoch:

* Per [Flattr](https://flattr.com/donation/give/to/sergej.mueller)
* Per [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RDDW9FEHGLG6)
* Empfehlung via Blogbeitrag
* Oder Tweet
* Oder oder

Sollte sich das Projekt mit zunehmender Nutzeranzahl nicht länger finanzieren können, werde ich auf ein kostenpflichtiges Modell umsteigen oder den Service einstellen müssen. Meine Vision ist jedoch, kostenlos und werbefrei zu bleiben, da alle Nutzer freiwillig mitmachen und unterstützen. Danke!


= Schlusswort =
Ich bin der Meinung, alle Vor- und Nachteile der Lösung aufgelistet zu haben. Sehr detailliert und transparent. Ist man mit aufgeführten Punkten oder Update-Zyklen nicht einverstanden, so möge man das Plugin NICHT in Betrieb nehmen. Konstruktive Vorschläge sind per E-Mail gerne willkommen.


= Systemanforderungen =
* PHP ab 5.2.4
* WordPress ab 3.4
* Ausgehende Verbindung
* Im Web erreichbarer Blog (kein localhost)


= Autor =
* [Google+](https://plus.google.com/110569673423509816572 "Google+")
* [Plugins](http://wpcoder.de "Plugins")
* [Portfolio](http://ebiene.de "Portfolio")



== Changelog ==

= 0.0.9 =
* Support für PNGs (Nutzer haben lieb gefragt ;)

= 0.0.8 =
* Beschränkung auf JPEGs (da fast keine PNG/GIF-Nutzung)
* Menge der optimierten Thumbnails als Diagramm, siehe [FAQ](http://wordpress.org/extend/plugins/optimus/faq/)
* Überarbeitung diverser Code-Fragmente

= 0.0.7 =
* Verbesserte Fehlerausgabe an den Nutzer

= 0.0.6 =
* Erweiterung der FAQ
* Unterstützung für WordPress 3.5
* Erhöhung des max. Limits auf 300 KB

= 0.0.5 =
* Zusatzabfrage für (korrupte) Bild-URLs
* Weiterer [Screenshot](http://wordpress.org/extend/plugins/optimus/screenshots/)

= 0.0.4 =
* Diverse Code-Optimierungen
* Reduzierung der Max-Größe

= 0.0.3 =
* Unterstützung für PHP 5.2.4

= 0.0.2 =
* Überarbeitung der GUI

= 0.0.1 =
* Init release



== Screenshots ==

1. Anzeige der Komprimierung in Prozent
2. Hochgeladene Thumbnails mit und ohne *Optimus*



== Frequently Asked Questions ==

= 1. Allgemein =
Jeder Missbrauch des Plugins und der Server-Software wird durch die Sperrung der IP-Adresse bestraft. Das gilt auch für Power-Nutzer, die keinerlei Unterstützung geleistet haben (Stichwort *Nehmen UND Geben*).


= 2. Länderbegrenzung =
Um den Missbrauch des Dienstes zu minimieren, werden alle Anfragen außerhalb von Deutschland, Österreich und der Schweiz blockiert. Wenn du einen Zugang zum Service außerhalb der freigegebenen Länder benötigst, melde dich beim [Entwickler](http://wpcoder.de).


= 3. Bulk-Optimierung =

Nein, eine Gruppen-Optimierung in WordPress vorhandener Bilder wird es vorerst nicht geben. Denn: Würden alle Plugin-Nutzer von der Funktion Gebrauch machen und Tausende an Bildern an den Server zwecks Optimierung schicken, würde es für diesen fatale Folgen haben.

Daher die Empfehlung: Optimiert eure bereits existierende Uploads mit einem Desktop-Tool wie beispielsweise [ImageOptim](http://playground.ebiene.de/png-bilder-optimieren/) für Mac (für Windows und Linux gibt es Alternativen). Denkbare Vorgehensweise:

* Den kompletten *uploads* Ordner auf den lokalen Rechner herunter laden.
* Eine Sicherung des Ordners anfertigen.
* Den Ordner auf das Optimierungstool ziehen.
* Alle Bilder im Ordner werden vom Tool optimiert (kann dauern).
* Nach der Optimierung den Ordner per FTP an den Ursprungsort übertragen.


= 4. Fehlermeldungen =
*Optimus* ist in der Lage, vom Server erhaltene Fehlermeldungen in der Mediathek abzubilden.

= 4.1. Fehlercode 403 =
Die Fehlermeldung besagt: Der Blog hat keine Berechtigung, Optimierungsanfragen an den *Optimus*-Server zu stellen. Oder etwas stimmt mit der Bild-URL nicht.


= 5. Häufig gestellte Fragen =

= 5.1. Das Originalbild wird nicht optimiert =
*Optimus* verfügt über einen Limit von aktuell 300 KB. Dateien, die diese Größe übersteigen, überspringen die geplante Optimierung. So passiert es schnell, dass Initialbilder in der Größe nicht reduziert werden. Das ist aber keinesfalls tragisch, da in Artikeln meist zugeschnittene, von *Optimus* komprimierte Miniaturbilder und selten Originalbilder eingebunden werden.

= 5.2. Was bedeutet der zum Teil ausgefüllte grüne Kreis? =
Da es bis dato nicht ersichtlich war, wie viele Vorschaubilder eines Bildes tatsächlich optimiert und welche wegen des oben erwähnten Limits von 300 kB übersprungen wurden, zeigt das grüne Diagramm die erfolgreiche Menge optisch dar, siehe [Screenshot](http://wordpress.org/extend/plugins/optimus/screenshots/): Ist der halbe Kreis in Grün, so befanden sich ca. 50 % der Bilder in der Optimierungsphase. Ist der Kreis komplett grün ausgefüllt, so wurden alle Thumbnails samt Originalbild in der Größe reduziert. Ein- und Dreiviertel analog zu verstehen.