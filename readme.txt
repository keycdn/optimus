=== Optimus ===
Contributors: sergej.mueller
Tags: images, optimize, compress, progressive, performance, png, jpeg, webp
Requires at least: 3.8
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Effektive Komprimierung der Bilder während des Upload-Vorgangs. Automatisch und ohne Qualitätsverlust.



== Description ==

= (Fast) Magie =
*Optimus* verkleinert die Dateigröße hochgeladener Medien. Abhängig vom Bild und Format ist eine Reduzierung der Größe um bis zu 70 Prozent möglich. Pro Bild lassen sich mehrere Kilobytes einsparen, die der Blogseiten-Performance positiv beitragen. Das Beeindruckende an der Kompressionstechnologie: Die Qualität der Bilder bleibt garantiert erhalten.


> #### Optimus HQ mit PREMIUM-Funktionen
> *Optimus* existiert in zwei Varianten:
> 1. Die kostenlos erhältliche Grundversion mit einigen Einschränkungen.
> 2. *Optimus HQ* mit erweitertem Funktionsumgang und unlimitiertem Traffic.
>
> Alle Informationen zum Produkt auf [optimus.io](https://optimus.io)


= Das Prinzip =
Während der Übertragung der Bilder in die Mediathek schickt das *Optimus*-Plugin die jeweiligen Bilder parallel an den *Optimus*-Server, der das eingegangene Material bearbeitet und in optimierter Form zurückgibt. Daraufhin speichert das *Optimus*-Plugin die in der Größe reduzierte Version eines Bildes in der Mediathek ab.

Die Optimierung der Bilder (samt Thumbnails) erfolgt im Hintergrund und ist für Nutzer optisch nahezu nicht wahrzunehmen. Den Kompressionsgewinn pro Datei stellt *Optimus* innerhalb der Mediathek in Form eines Prozentwertes dar, siehe [Screenshot](https://wordpress.org/plugins/optimus/screenshots/).

Anders als andere Tools rührt *Optimus* die Qualität der Fotos niemals an. Stattdessen werden aus Bilddateien überflüssige Informationen extrahiert, die von Bildprogrammen (mit)gespeichert werden und zur Darstellung überhaupt nicht notwendig sind. Auf diese Art bleibt die Qualität der Grafiken erhalten, die Dateigröße kann um ein Vielfaches verkleinert werden. Auf Wunsch behält *Optimus* alle Autor-, EXIF- und Copyright-Informationen innerhalb der Fotos - der Kompressionsgewinn fällt entsprechend kleiner aus.

NEU für Optimus HQ: Optionale [Konvertierung](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/3emb7o4368X) der Bilder ins sparsame [WebP-Bildformat](http://cup.wpcoder.de/webp-jpeg-alternative/).


= Pluspunkte =
* [Progressive JPEGs](https://plus.google.com/114450218898660299759/posts/RPW48vHbwoM)
* Verkleinerung der Dateigröße ohne Verlust der Qualität
* Optimierung aller Vorschauvarianten eines Bildes
* Keine Code-Anpassungen notwendig
* Option: Keine Entfernung von EXIF- und IPTC-Metadaten
* Option: HTTPS-Verbindung für die Bildübertragung (Optimus HQ)
* Option: Konvertierung ins WebP-Bildformat (Optimus HQ)
* Optimiert für WordPress Mobile Apps und Windows Live Writer
* Vorteilhafterer PageSpeed als Ranking-Faktor
* Geringere Ladezeit der Blogseiten
* WordPress Multisite-fähig


= Datenschutz =
* Nach der Optimierung und Auslieferung der Bilder löscht der *Optimus*-Server die temporär angelegten Dateien unverzüglich. Keine Aufbewahrung!
* Der Server-Standort ist Deutschland beim Hoster domainFACTORY.


= Tipps =
* Fotos stets als JPEGs statt PNGs speichern. Das PNG-Format ist sinnvoll für Illustrationen, JPEG ist dagegen genau das Richtige für Fotoaufnahmen. Weiterer Grund: Die Größenreduzierung geht bei JPEGs flotter vonstatten.
* Zahlreiche Blogger optimieren ihre Bilder vor dem Upload mit Desktop-Tools wie [ImageOptim](http://playground.ebiene.de/png-bilder-optimieren/). *Optimus* hat den gravierenden Vorteil, dass von WordPress (Theme & Plugins) erstellte Thumbnails (= Vorschaubilder) von *Optimus* ebenfalls behandelt und minimiert werden. Schliesslich sind im Theme fast immer Thumbnails eingebunden und nur selten das Originalbild.


= Systemanforderungen =
* PHP ab 5.2.4
* WordPress ab 3.8
* Ausgehende Serververbindung


= Speicherbelegung =
* Im Backend: ~ 0,19 MB
* Im Frontend: ~ 0,03 MB


= Website =
* [optimus.io](https://optimus.io)


= Autor =
* [Twitter](https://twitter.com/wpseo)
* [Google+](https://plus.google.com/110569673423509816572 "Google+")
* [Plugins](http://wpcoder.de "Plugins")



== Changelog ==

= 1.3.4 =
* Umstellung des Plugins auf die neue Optimus API (cURL only)

= 1.3.3 =
* Kompatibilität zu WooCommerce
* Einführung von Optimus HQ PRO
* [Ausführlich auf Google+](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/iNDtLhWw4p2)

= 1.3.2 =
* Anzeige der in WordPress registrierten Bildgrößen (Thumbnails)
* [Ausführlich auf Google+](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/jZVfeac5eHW)

= 1.3.1 =
* Anpassung für WordPress 3.9: Sicherstellung der Bildoptimierung im WordPress-Editor

= 1.3.0 =
* Überarbeitung der Kommunikation via cURL
* Implementierung von HTTPS (Optimus HQ)
* Zusätzliche Checks beim Versand und Empfang der Daten
* Vereinfachung der Feedback-Ausgabe in grünen Kreisen
* Kein Abgleich der Davor-Danach-Bildgrößen für 204-Header
* Connection-Timeout auf 10 Sekunden
* Tiefgehende Code-Revision

= 1.2.0 =
* Optimierungen am Plugin-Rechtemanagement
* Überarbeitung der Plugin-Hinweise
* [Ausführlich auf Google+](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/2eynLwEsedi)

= 1.1.9 =
* Optimus HQ: Umbau der Lizenzverifizierung wegen einem [Bug](https://www.google.de/search?q=w3+total+cache+transient) im W3 Total Cache Plugin
* Ausbau der Schnittstelle für binäre Datenübertragung

= 1.1.8 =
* Anzeige des Optimus HQ Ablaufdatums
* Admin-Hinweis bei abgelaufener Optimus HQ Lizenz
* [Ausführlich auf Google+](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/9UGqj6UPjLv)

= 1.1.7 =
* Christmas-Edition (Code- & Performance-Optimierungen)

= 1.1.6 =
* Optimiert für WordPress 3.8

= 1.1.5 =
* Optimus HQ: Eingabe eines neuen Lizenzschlüssels möglich
* Selbstprüfung auf Erreichbarkeit der Upload-Bilder (Zugriffsschutz, etc.)
* Performance-Optimierungen
* [Ausführlich auf Google+](https://plus.google.com/114450218898660299759/posts/6nyJ3kPnFPu)

= 1.1.4 =
* Optimus HQ: Erhöhung des Limits für PNGs auf 200 KB
* Optimus HQ: Konvertierung der Bilder ins [WebP-Format](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/3emb7o4368X)

= 1.1.3 =
* Unterstützung für WordPress Apps und Windows Live Writer / [Ausführlich auf Google+](https://plus.google.com/114450218898660299759/posts/CDAc5FoDioN)

= 1.1.2 =
* Option: Bild-Metadaten nicht entfernen / [Ausführlich auf Google+](https://plus.google.com/114450218898660299759/posts/Nu8SLUwvNSS)

= 1.1.1 =
* Interne Umstellung auf Mime-Type
* Code-Optimierungen

= 1.1.0 =
* Umstellung auf Freemium Modell / [Offizielles Statement](https://plus.google.com/110569673423509816572/posts/XEoHhEi5uJw)
* Plugin Website: [optimus.io](https://optimus.io)

= 1.0.0 =
* Code-Freeze
* Vervollständigung der FAQ
* Filter für lokale Installationen

= 0.0.9 =
* Support für PNGs

= 0.0.8 =
* Beschränkung auf JPEGs (da fast keine PNG/GIF-Nutzung)
* Menge der optimierten Thumbnails als Diagramm
* Überarbeitung diverser Code-Fragmente

= 0.0.7 =
* Verbesserte Fehlerausgabe an den Nutzer

= 0.0.6 =
* Erweiterung der FAQ
* Unterstützung für WordPress 3.5
* Erhöhung des max. Limits auf 300 KB

= 0.0.5 =
* Zusatzabfrage für (korrupte) Bild-URLs
* Weiterer [Screenshot](https://wordpress.org/plugins/optimus/screenshots/)

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

1. Anzeige der Komprimierung und der Bildmenge als Kreis
2. Bild samt Thumbnails ohne (oben) und mit Optimus Optimierung