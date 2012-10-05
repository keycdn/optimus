=== Optimus ===
Contributors: sergej.mueller
Tags: images, optimize, smush.it, compress
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RDDW9FEHGLG6
Requires at least: 3.4
Tested up to: 3.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Effektive Komprimierung der Bilder während des Upload-Vorgangs. Automatisch und ohne Qualitätsverlust.



== Description ==

= Tschüss, Smush.it =
*Optimus* übernimmt die automatische Komprimierung der hochgeladenen Medien. Die Dateigröße reduziert sich um bis zu 50 Prozent, die Qualität des Bildes bleibt garantiert erhalten. Praktisch und effektiv.


= Prinzip =
Während der Übertragung der Bilder in die Mediathek schickt das *Optimus*-Plugin die URL des Bildes an den entfernten *Optimus*-Server, der das Bild einliest, bearbeitet und in optimierter Form zurückgibt. Daraufhin speichert das *Optimus*-Plugin die komprimierte Ausgabe in der Mediathek.

Die Optimierung der Bilder (samt Thumbnails) erfolgt im Hintergrund und ist für Nutzer optisch nicht wahrzunehmen. Den Kompressionsgewinn pro Datei stellt *Optimus* innerhalb der Mediathek in Form eines Prozentwertes dar. An der gleichen Stelle werden auch Fehler abgebildet.


= Vorteile =
* Verkleinerung der Dateigröße ohne Verlust der Qualität
* Optimierung aller Vorschauvarianten eines Bildes
* Keine Einstellungen oder Code-Anpassungen notwendig
* Vorteilhafterer PageSpeed als Ranking-Faktor
* Geringere Ladezeit der Blogseiten
* Kostenlos und werbefrei


= Bonustipp =
Zahlreiche Blogger optimieren ihre Bilder händisch vor dem Upload mit Desktop-Tools wie ImgOptim. *Optimus* hat einen gravierenden Vorteil, dass auch von WordPress erstellte Thumbnails eines Bildes behandelt und minimiert werden. Schliesslich werden im Theme fast immer Thumbnails (= Vorschaubilder) eingebunden und nur selten das Originalbild.


= Beschränkungen =
* Bilder in folgenden Formaten werden komprimiert: PNG, GIF und JPEG.
* Bilder über 2 MB werden ignoriert, Vorschauvarianten dennoch optimiert.
* Durch den notwendigen Transfer der Bilder zum *Optimus*-Server verzögert sich die Fertigstellung des Upload-Vorgangs.
* Keep it simple: *Optimus* kommt ohne einer Optionsseite aus.


= Datenschutz =
* Nach der Optimierung und Auslieferung der optimierten Bilder löscht der *Optimus*-Server die temporär abgelegten Dateien - unverzüglich. Keine Aufbewahrung!
* Während der Kommunikation zum *Optimus*-Server teilt das Plugin die aktuelle Blog-URL mit. Auf diese Weise soll der Missbrauch der Software erkannt und Power-Nutzer identifiziert werden.
* Der Server-Standort ist Deutschland beim Hoster domainFACTORY.


= Warum mache ich das? =
smush.it hat über Jahre hinweg einen perfekten Dienst geleistet. In letzter Zeit hat die Reaktionszeit des Dienstes sehr nachgelassen, so dass selten ein Bild zuverlässig optimiert wurde. Mit *Optimus* stelle ich eine geschwinde Alternative zur Verfügung, die jedoch nicht wie smush.it enden soll. Daher meine Bitte: Missbraucht den Service nicht. Ich gebe euch ein feines und unkompliziertes Plugin in die Hände und erwarte Respekt und Kooperation.

Zurzeit finanziere ich das Projekt aus Einnahmen meines [SEO-Plugins](http://wpseo.de). Performance-Server kosten Geld. Über die Unterstützung jeder Art freue ich mich dennoch:

* Per [Flattr](http://flattr.com/profile/sergej.mueller)
* Per [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RDDW9FEHGLG6)
* Empfehlung via Blogbeitrag
* Oder oder

Sollte sich das Projekt mit zunehmender Nutzeranzahl nicht länger finanzieren können, werde ich auf ein kostenpflichtiges Modell umsteigen oder den Service einstellen müssen. Meine Vision ist jedoch, kostenlos und werbefrei zu bleiben, da alle Nutzer freiwillig mitmachen und unterstützen. Danke!


= Schlusswort =
Ich bin der Meinung, alle Vor- und Nachteile der Lösung aufgelistet zu haben. Sehr detailliert und transparent. Ist man mit aufgeführten Punkten nicht einverstanden, so möge man das Plugin NICHT in Betrieb nehmen. Konstruktive Vorschläge sind per E-Mail gerne willkommen.


= Systemanforderungen =
* PHP ab 5.2.4
* WordPress ab 3.4
* Ausgehende Verbindung


= Autor =
* [Google+](https://plus.google.com/110569673423509816572 "Google+")
* [Plugins](http://wpcoder.de "Plugins")
* [Portfolio](http://ebiene.de "Portfolio")



== Changelog ==

= 0.0.3 =
* Unterstützung für PHP 5.2.4

= 0.0.2 =
* Überarbeitung der GUI

= 0.0.1 =
* Init release



== Screenshots ==

1. Anzeige der Komprimierung in Prozent