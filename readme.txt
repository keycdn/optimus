=== Optimus ===
Contributors: sergej.mueller
Tags: images, optimize, compress, smushit, webp
Requires at least: 3.8
Tested up to: 3.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Effektive Komprimierung der Bilder während des Upload-Vorgangs. Automatisch und ohne Qualitätsverlust.



== Description ==

= (Fast) Magie =
Das *Optimus*-Plugin verkleinert die Dateigröße hochgeladener Medien. Abhängig vom Bild und Format ist eine Reduzierung der Größe um bis zu 70 Prozent möglich. Pro Bild lassen sich mehrere Kilobyte einsparen, die der Performance von Blogseiten positiv beitragen. Das Beeindruckende an der Kompressionstechnologie: Die Qualität der Bilder bleibt garantiert erhalten.


> #### Optimus HQ mit PREMIUM-Funktionen
> Optimus existiert in zwei Varianten: Die kostenlos erhältliche Grundversion mit einigen Einschränkungen und *Optimus HQ* nahezu ohne Limitierungen. Alle Informationen zum Produkt auf [optimus.io](http://optimus.io)


= Prinzip =
Während der Übertragung der Bilder in die Mediathek schickt das *Optimus*-Plugin die URL des Bildes an den *Optimus*-Server, der das Bild einliest, bearbeitet und in optimierter Form zurückgibt. Daraufhin speichert das *Optimus*-Plugin die entgegengenommene - in der Größe reduzierte - Version des Bildes in der Mediathek ab.

Die Optimierung der Bilder (samt Thumbnails) erfolgt im Hintergrund und ist für Nutzer optisch nahezu nicht wahrzunehmen. Den Kompressionsgewinn pro Datei stellt *Optimus* innerhalb der Mediathek in Form eines Prozentwertes dar, siehe [Screenshot](http://wordpress.org/extend/plugins/optimus/screenshots/).

Anders als andere Tools rührt *Optimus* die Qualität der Fotos niemals an. Stattdessen werden aus Bilddateien überflüssige Informationen extrahiert, die von Bildprogrammen (mit)gespeichert werden und zur Darstellung überhaupt nicht notwendig sind. Auf diese Art bleibt die Qualität der Grafiken erhalten, die Dateigröße kann um ein Vielfaches minimiert werden. Auf Wunsch behält *Optimus* alle Autor-, EXIF- und Copyright-Informationen innerhalb des Fotos - der Kompressionsgewinn fällt entsprechend kleiner aus.

NEU für Optimus HQ: Optionale [Konvertierung](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/3emb7o4368X) der Bilder ins sparsame [WebP-Bildformat](http://cup.wpcoder.de/webp-jpeg-alternative/).


= Pluspunkte =
* Verkleinerung der Dateigröße ohne Verlust der Qualität
* Optimierung aller Vorschauvarianten eines Bildes
* Keine Code-Anpassungen notwendig
* Option: Keine Entfernung von EXIF- und IPTC-Metadaten
* Option: Konvertierung ins WebP-Bildformat
* Auch für Bildübertragungen aus WordPress Apps und Windows Live Writer
* Vorteilhafterer PageSpeed als Ranking-Faktor
* Geringere Ladezeit der Blogseiten


= Datenschutz =
* Nach der Optimierung und Auslieferung der Bilder löscht der *Optimus*-Server die temporär angelegten Dateien - unverzüglich. Keine Aufbewahrung!
* Der Server-Standort ist Deutschland beim Hoster domainFACTORY.


= Tipps =
* Fotos stets als JPEGs statt PNGs speichern. Das PNG-Format ist sinnvoll für Illustrationen, JPEG ist dagegen genau das Richtige für Fotoaufnahmen. Weiterer Grund: Die Größenreduzierung geht bei JPEGs flotter vonstatten.
* Zahlreiche Blogger optimieren ihre Bilder vor dem Upload mit Desktop-Tools wie [ImageOptim](http://playground.ebiene.de/png-bilder-optimieren/). *Optimus* hat den gravierenden Vorteil, dass von WordPress erstellte Thumbnails (= Vorschaubilder) eines Bildes ebenfalls behandelt und minimiert werden. Schliesslich sind im Theme fast immer Thumbnails eingebunden und nur selten das Originalbild.


= Hintergrund =
Das *Smush.it Plugin* hat über Jahre hinweg einen perfekten Dienst geleistet. In letzter Zeit hat die Reaktionszeit des Dienstes sehr nachgelassen, so dass selten ein Bild zuverlässig optimiert wurde. Mit *Optimus* wird eine geschwinde Alternative zur Verfügung, die jedoch nicht wie *Smush.it* enden soll. Daher die Bitte: Missbraucht den Service nicht. Danke!


= Support =
Freundlich formulierte Fragen rund um das Plugin werden per E-Mail beantwortet.


= Systemanforderungen =
* PHP ab 5.2.4
* WordPress ab 3.8
* Ausgehende Serververbindung


= Website =
* [optimus.io](http://optimus.io)


= Autor =
* [Google+](https://plus.google.com/110569673423509816572 "Google+")
* [Plugins](http://wpcoder.de "Plugins")



== Changelog ==

= 1.1.9 =
* Optimus HQ: Umbau der Lizenzverifizierung wegen einem Bug im W3 Total Cache Plugin
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
* Plugin Website: [optimus.io](http://optimus.io)

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



== Upgrade Notice ==

= 1.1.0 =
*Optimus* wurde auf Freemium Modell umgestellt. [Offizielles Statement](https://plus.google.com/110569673423509816572/posts/XEoHhEi5uJw).



== Screenshots ==

1. Anzeige der Komprimierung und der Bildmenge als Kreis
2. Bild samt Thumbnails ohne (oben) und mit Optimus Optimierung