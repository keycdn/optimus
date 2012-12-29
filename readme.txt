=== Optimus ===
Contributors: sergej.mueller
Tags: images, optimize, compress
Requires at least: 3.4
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Effektive Komprimierung der Bilder während des Upload-Vorgangs. Automatisch und ohne Qualitätsverlust.



== Description ==

= Goodbye Smush.it =
Das *Optimus*-Plugin verkleinert die Dateigröße hochgeladener Medien. Abhängig vom Bild und Format ist eine Reduzierung der Größe um bis zu 70 Prozent möglich. Pro Bild lassen sich mehrere Kilobyte einsparen, die der Performance von Blogseiten positiv beitragen. Das Beeindruckende an der Kompressionstechnologie: Die Qualität der Bilder bleibt garantiert erhalten.


> #### Optimus HQ mit PREMIUM-Funktionen
> Optimus existiert in zwei Varianten: Die kostenlos erhältliche Grundversion mit einigen Einschränkungen und *Optimus HQ* nahezu ohne Limitierungen. Alle Informationen zum Produkt auf [optimus.io](http://optimus.io)


= Prinzip =
Während der Übertragung der Bilder in die Mediathek schickt das *Optimus*-Plugin die URL des Bildes an den entfernten *Optimus*-Server, der das Bild einliest, bearbeitet und in optimierter Form zurückgibt. Daraufhin speichert das *Optimus*-Plugin die entgegengenommene - in der Größe reduzierte - Version des Bildes in der Mediathek ab.

Die Optimierung der Bilder (samt Thumbnails) erfolgt im Hintergrund und ist für Nutzer optisch nahezu nicht wahrzunehmen. Den Kompressionsgewinn pro Datei stellt *Optimus* innerhalb der Mediathek in Form eines Prozentwertes dar, siehe [Screenshot](http://wordpress.org/extend/plugins/optimus/screenshots/).

Anders als andere Tools rührt *Optimus* die Qualität der Fotos niemals an. Stattdessen werden aus Bilddateien überflüssige Informationen extrahiert, die von Bildprogrammen (mit)gespeichert werden und zur Darstellung überhaupt nicht notwendig sind. Auf diese Art bleibt die Qualität der Grafiken erhalten, die Dateigröße kann um ein Vielfaches minimiert werden.


= Pluspunkte =
* Verkleinerung der Dateigröße ohne Verlust der Qualität
* Optimierung aller Vorschauvarianten eines Bildes
* Keine Einstellungen oder Code-Anpassungen notwendig
* Vorteilhafterer PageSpeed als Ranking-Faktor
* Geringere Ladezeit der Blogseiten


= Datenschutz =
* Nach der Optimierung und Auslieferung der Bilder löscht der *Optimus*-Server die temporär abgelegten Dateien - unverzüglich. Keine Aufbewahrung!
* Der Server-Standort ist Deutschland beim Hoster domainFACTORY.


= Tipps =
* Fotos stets als JPEGs statt PNGs speichern. Das PNG-Format ist sinnvoll für Illustrationen, JPEG ist dagegen genau das Richtige für Fotoaufnahmen. Weiterer Grund: Die Größenreduzierung geht bei JPEGs flotter vonstatten.
* Zahlreiche Blogger optimieren ihre Bilder vor dem Upload mit Desktop-Tools wie [ImageOptim](http://playground.ebiene.de/png-bilder-optimieren/). *Optimus* hat den gravierenden Vorteil, dass von WordPress erstellte Thumbnails (= Vorschaubilder) eines Bildes ebenfalls behandelt und minimiert werden. Schliesslich sind im Theme fast immer Thumbnails eingebunden und nur selten das Originalbild.


= Hintergrund =
Das *Smush.it Plugin* hat über Jahre hinweg einen perfekten Dienst geleistet. In letzter Zeit hat die Reaktionszeit des Dienstes sehr nachgelassen, so dass selten ein Bild zuverlässig optimiert wurde. Mit *Optimus* wird eine geschwinde Alternative zur Verfügung, die jedoch nicht wie *Smush.it* enden soll. Daher die Bitte: Missbraucht den Service nicht. Danke!


= Systemanforderungen =
* PHP ab 5.2.4
* WordPress ab 3.4
* Ausgehende Verbindung
* Im Web erreichbare Bilder

= Autor =
* [Google+](https://plus.google.com/110569673423509816572 "Google+")
* [Plugins](http://wpcoder.de "Plugins")

= Plugin Website =
* [optimus.io](http://optimus.io)


== Changelog ==

= 1.1.0 =
* Umstellung auf Freemium Modell / [Offizielles Statement](http://wordpress.org/extend/plugins/optimus/faq/)
* Plugin Website: [optimus.io](http://optimus.io)

= 1.0.0 =
* Code-Freeze
* Vervollständigung der FAQ
* Filter für lokale Installationen

= 0.0.9 =
* Support für PNGs

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



== Upgrade Notice ==

= 1.1.0 =
*Optimus* wurde auf Freemium Modell umgestellt. [Offizielles Statement](http://wordpress.org/extend/plugins/optimus/faq/) des Entwicklers.



== Screenshots ==

1. Anzeige der Komprimierung in Prozent
2. Hochgeladene Thumbnails mit und ohne *Optimus*



== Frequently Asked Questions ==

= Neuanfang für Optimus =
*Optimus* wurde auf [Freemium](http://de.wikipedia.org/wiki/Freemium) Modell umgestellt. Optimus gibt es nun in zwei Varianten: Weiterhin die kostenlose Fassung des Plugins mit einigen Beschränkungen und Optimus HQ als kostenpflichtige Alternative mit höher gesetzten Limits. Alle Einzelheiten zu den beiden Varianten auf der offiziellen Optimus Website [optimus.io](http://optimus.io).

= Die berechtigte Frage nach „Warum?“ =
In der Plugin-Beschreibung habe ich euch explizit darauf hingewiesen, dass der *Optimus Server* richtiges Geld kostet. Die Kosten war ich bereit (zum Teil) selbst zu tragen, war dennoch auf eure Unterstützung angewiesen. Im ersten Monat hat es wunderbar geklappt, danach aber auch nicht mehr. Ich betone: Es musste nicht immer finanzielle Unterstützung sein. Aber ganz ohne geht’s nicht. Das akzeptiere ich so nicht. Wer (viel) nimmt, muss auch geben können.

Ich kann Nutzer mehr als verstehen, die sagen „Warum soll ich ein Projekt unterstützen und andere profitieren nur davon?“ Weil viele der Nutzer die Kaufbereitschaft signalisiert haben, gibt's nun die kaufbare *Optimus* Version.

Das Prinzip: Ist jemand mit Weniger zufrieden, genügt *Optimus* in der Grundversion. Ist man professionell unterwegs, ist *Optimus HQ* ein zuverlässiger Begleiter bei der Optimierung der Bildgröße und Seitenladezeit.

Der Preis ist sehr fair und transparent - diesen kann sich wirklich jeder leisten. Bei der Preisgestaltung ging es mir nicht darum Profit zu schlagen, sondern die Hardware-Kosten zu decken. Vom Support- und Weiterentwicklungsaufwand spreche ich hier gar nicht - diese gibt’s wie immer *on top*.

In diesem Sinne: Schimpft nicht, versucht zu verstehen. Meine andere Plugins können gerne kostenlos bleiben. Doch sobald ein Kostenfaktor wie Hardware hinzukommt, will ich auf entstehenden Kosten nicht sitzen bleiben.

Details zu Optimus HQ unter [optimus.io](http://optimus.io)