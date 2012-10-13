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
Um den Support-Aufwand zu reduzieren, liest euch die Plugin-Beschreibung bis zum Ende durch. Beachtet auch die [FAQ](http://wordpress.org/extend/plugins/optimus/faq/).


= Prinzip =
Während der Übertragung der Bilder in die Mediathek schickt das *Optimus*-Plugin die URL des Bildes an den entfernten *Optimus*-Server, der das Bild einliest, bearbeitet und in optimierter Form zurückgibt. Daraufhin speichert das *Optimus*-Plugin die komprimierte Ausgabe in der Mediathek.

Die Optimierung der Bilder (samt Thumbnails) erfolgt im Hintergrund und ist für Nutzer optisch nicht wahrzunehmen. Den Kompressionsgewinn pro Datei stellt *Optimus* innerhalb der Mediathek in Form eines Prozentwertes dar, siehe [Screenshot](http://wordpress.org/extend/plugins/optimus/screenshots/). An der gleichen Stelle werden auch Fehler abgebildet.


= Vorteile =
* Verkleinerung der Dateigröße ohne Verlust der Qualität
* Optimierung aller Vorschauvarianten eines Bildes
* Keine Einstellungen oder Code-Anpassungen notwendig
* Vorteilhafterer PageSpeed als Ranking-Faktor
* Geringere Ladezeit der Blogseiten
* Kostenlos und werbefrei


= Bonustipp =
Zahlreiche Blogger optimieren ihre Bilder händisch vor dem Upload mit Desktop-Tools wie ImageOptim. *Optimus* hat einen gravierenden Vorteil, dass auch von WordPress erstellte Thumbnails eines Bildes behandelt und minimiert werden. Schliesslich werden im Theme fast immer Thumbnails (= Vorschaubilder) eingebunden und nur selten das Originalbild.


= Empfehlung =
*Optimus* mag Bilder im JPEG-Format besonders - diese lassen sich in der Größe am schnellsten reduzieren. Speichert eure Fotos daher als JPEG statt PNG. PNGs sind eher für Illustrationen geeignet.


= Einschränkungen =
* Nur Server-Anfragen aus DE, AT und CH werden bearbeitet.
* Bilder in folgenden Formaten werden komprimiert: PNG, GIF und JPEG.
* Bilder über 300 KB werden ignoriert, Vorschauvarianten dennoch optimiert.
* Durch den notwendigen Transfer der Bilder zum *Optimus*-Server verzögert sich die Fertigstellung des Upload-Vorgangs.
* Keep it simple: *Optimus* kommt ohne einer Optionsseite aus.


= Datenschutz =
* Nach der Optimierung und Auslieferung der optimierten Bilder löscht der *Optimus*-Server die temporär abgelegten Dateien - unverzüglich. Keine Aufbewahrung!
* Während der Kommunikation zum *Optimus*-Server teilt das Plugin die aktuelle Blog-URL mit. Auf diese Weise soll der Missbrauch der Software erkannt und Power-Nutzer identifiziert werden.
* Der Server-Standort ist Deutschland beim Hoster domainFACTORY.


= Warum mache ich das? =
smush.it hat über Jahre hinweg einen perfekten Dienst geleistet. In letzter Zeit hat die Reaktionszeit des Dienstes sehr nachgelassen, so dass selten ein Bild zuverlässig optimiert wurde. Mit *Optimus* stelle ich eine geschwinde Alternative zur Verfügung, die jedoch nicht wie smush.it enden soll. Daher meine Bitte: Missbraucht den Service nicht. Ich gebe euch ein feines und unkompliziertes Plugin in die Hände und erwarte Respekt und Kooperation.

= Finanzierung =
Zurzeit finanziere ich das Projekt aus Einnahmen meines [SEO-Plugins](http://wpseo.de). Performance-Server kosten Geld. Über die Unterstützung jeder Art freue ich mich dennoch:

* Per [Flattr](https://flattr.com/donation/give/to/sergej.mueller)
* Per [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5RDDW9FEHGLG6)
* Empfehlung via Blogbeitrag
* Oder Tweet
* Oder oder

Sollte sich das Projekt mit zunehmender Nutzeranzahl nicht länger finanzieren können, werde ich auf ein kostenpflichtiges Modell umsteigen oder den Service einstellen müssen. Meine Vision ist jedoch, kostenlos und werbefrei zu bleiben, da alle Nutzer freiwillig mitmachen und unterstützen. Danke!


= Schlusswort =
Ich bin der Meinung, alle Vor- und Nachteile der Lösung aufgelistet zu haben. Sehr detailliert und transparent. Ist man mit aufgeführten Punkten nicht einverstanden, so möge man das Plugin NICHT in Betrieb nehmen. Konstruktive Vorschläge sind per E-Mail gerne willkommen.


= Systemanforderungen =
* PHP ab 5.2.4
* WordPress ab 3.4
* Ausgehende Verbindung
* Im Web erreichbarer Blog (keine lokale Installationen)


= Autor =
* [Google+](https://plus.google.com/110569673423509816572 "Google+")
* [Plugins](http://wpcoder.de "Plugins")
* [Portfolio](http://ebiene.de "Portfolio")



== Changelog ==

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
Seit der Plugin-Veröffentlichung versuchen Spammer und „Trickser“ aus ganzer Welt den Dienst zu missbrauchen und an eigene Bedürfnisse anzupassen. Das Resultat: Der Server erfährt seinen Limit und der Traffic katapultiert in die Höhe. Als Reaktion werden ab sofort ausschließlich Server-Anfragen aus 3 Ländern zugelassen: Deutschland, Österreich und die Schweiz.


= 3. Bulk-Optimierung =

Nein, eine Gruppen-Optimierung in WordPress vorhandener Bilder wird es vorerst nicht geben. Denn: Würden alle Plugin-Nutzer von der Funktion Gebrauch machen und Tausende an Bildern an den Server zwecks Optimierung schicken, würde es für diesen fatale Folgen haben.

Daher die Empfehlung: Optimiert eure bereits existierende Uploads mit einem Desktop-Tool wie beispielsweise [ImageOptim](http://playground.ebiene.de/png-bilder-optimieren/) für Mac (für Windows und Linux gibt es Alternativen). Denkbare Vorgehensweise:

* Den kompletten *uploads* Ordner auf den lokalen Rechner herunter laden.
* Eine Sicherung des Ordners anfertigen.
* Den Ordner auf das Optimierungstool ziehen.
* Alle Bilder im Ordner werden vom Tool optimiert (kann dauern).
* Nach der Optimierung den Ordner per FTP an den Ursprungsort übertragen.


= 4. Fehlermeldungen =
*Optimus* ist in der Lage, vom Server erhaltene Fehlermeldungen in der Mediathek abzubilden. Nachfolgend einige der Hinweise:

= 4.1. Unerreichbare Datei =
Das zur Optimierung übergebene Bild konnte nicht eingelesen werden. Folgende Ursachen können dafür verantwortlich sein:

* Geschütztes Upload-Verzeichnis
* Bilder werden nicht in WordPress, sondern extern (z.B. CDN) abgelegt

= 4.2. Ungültige URL =
Bilder mit manipulierten, unvollständigen oder nicht WordPress-konformen Pfaden werden abgewiesen und nicht bearbeitet. Anfragen aus localhost-Instanzen können ebenfalls nicht entgegen genommen werden.

= 4.3. Fehlercode 403 - 444 =
Die Fehlermeldung besagt: Der Blog hat keine Berechtigung, Optimierungsanfragen an den *Optimus*-Server zu stellen. Bitte das *Optimus*-Plugin deinstallieren.


= 5. Häufig gestellte Fragen =

= 5.1. Das Originalbild wird nicht optimiert =

*Optimus* verfügt über einen Limit von aktuell 300 KB. Dateien, die diese Größe übersteigen, überspringen die geplante Optimierung. So passiert es schnell, dass Initialbilder in der Größe nicht reduziert werden.

Das ist aber keinesfalls tragisch, da in Artikeln meist zugeschnittene, von *Optimus* komprimierte Miniaturbilder und selten Originalbilder eingebunden werden.