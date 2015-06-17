=== Optimus ===
Contributors: keycdn
Tags: images, optimize, compress, progressive, performance, png, jpeg, webp
Requires at least: 3.8
Tested up to: 4.2.2
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html



Effective image compression and optimization during the upload process. Automatic and without loss of quality.



== Description ==

= (Fast) Magie =
*Optimus* verkleinert die Dateigröße hochgeladener Medien. Abhängig vom Bild und Format ist eine Reduzierung der Größe um bis zu 70 Prozent möglich. Pro Bild lassen sich mehrere Kilobytes einsparen – diese Sparmaßnahme trägt der Blogseiten-Performance positiv bei. Das Beeindruckende an der Kompressionstechnologie: Die Qualität der Bilder bleibt garantiert erhalten. Alle Details zum Funktionsumfang und den einzelnen Preismodellen auf [optimus.io](https://optimus.io)


= (Almost) Magic =
Optimus reduces the file size of uploaded media files. Depending on the image and format, reductions in size of up to 70% are possible. Several kilobytes can be saved per image—these savings contribute positively to the performance of the blog website. What’s most impressive about the compression technology: the quality of the images is maintained—guaranteed.


> #### There are three different versions of Optimus:
> 1. **Optimus** *(Free)* as base version with limitations (e.g. a maximum of 100kb for JPEGs)
> 2. **Optimus HQ** *(Premium)* with expanded functionality for personal projects
> 3. **Optimus HQ PRO** *(Premium)* as professional solution for customer websites
>
> More details about the features and the pricing model on [optimus.io](https://optimus.io/en/)


= How does it work? =
During the uploading process of images to the media library, the *Optimus* plugin simultaneously sends the images to the *Optimus* server, where the incoming material is processed and sent back in optimized form. Afterwards, the *Optimus* plugin saves the image version with a reduced file size in the media library.

The optimization of images - *including thumbnails* - is conducted in the background and outside of the view of the user. The compression gains per file are displayed by Optimus within the media library in form of a percentage value, see [screenshot](https://wordpress.org/plugins/optimus/screenshots/).

Differently from common optimization tools, Optimus never alters the quality of images. Instead, superfluous information that is saved by image processing programs and is not even necessary for displaying the image is extracted from image files. This way, the quality of the graphics remains intact while the image size can be reduced significantly. If you wish, Optimus keeps all author, EXIF and copyright information contained within the photos — the compressional gains will be correspondingly lower.

Optimus optional support the conversion of images to the thrifty *WebP* image format.


= Features =
* [Progressive JPEGs](https://optimus.io/support/progressive-jpegs/)
* Reduction of file size *without loss in quality*
* Optimization of all preview images of a photo
* No adjustments to code necessary
* Optional: no removal of EXIF and IPTC metadata
* Optional: HTTPS connection for the image transfer (Optimus HQ)
* Optional: [conversion to the WebP](https://optimus.io/support/convert-jpeg-and-png-to-webp-image-format/) image format (Optimus HQ)
* Optimized for WordPress Mobile Apps and Windows Live Writer
* More advantageous PageSpeed, influencing the Ranking Factor
* Faster load times for blog pages
* Support for WooCommerce
* WordPress multisite-support


= Privacy =
* After the optimization and transfer process, the *Optimus* server immediately deletes all the temporarily stored files. No files are stored!
* The Optimus servers are located in Germany.


= Tips =
* Photos should always be saved as JPEGs rather than PNGs. The PNG format works well for illustrations, JPEG on the other hand is the right choice for photographs. Another reason: the size reduction always works more quickly for JPEGs.
* Your images have been optimized using Desktop tools such as ImageOptim (Mac) or PNGGauntlet (Win) before you upload them? Optimus has the significant benefit of also minimizing the thumbnails (=preview images) created by WordPress. After all, themes almost always integrate thumbnails rather than original images.


= Translations =
* English (default)
* German

*Note:* Optimus is localized. Please contribute your language to the plugin to make it even more awesome.


= System Requirements =
* PHP >=5.2.4
* WordPress >=3.8
* Allow outbound connections


= Storage Utilization =
* Backend: ~ 0,19 MB
* Frontend: ~ 0,01 MB


= Website =
* [optimus.io](https://optimus.io)


= Author =
* [Twitter](https://twitter.com/keycdn)
* [Google+](https://plus.google.com/+Keycdn "Google+")
* [KeyCDN](https://www.keycdn.com "KeyCDN")



== Changelog ==

= 1.3.7 =
* Erhöhung der Limits für alle Formate
* [Limits auf optimus.io](https://optimus.io)

= 1.3.6 =
* Neue Option: Keine Optimierung der Originalbilder
* Korrektur: Löschung der WebP-Bilder im AJAX-Modus
* [Ausführlich auf Google+](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/hWjiVYht9yF)

= 1.3.5 =
* Erhöhung des Limits für PNG-Dateien auf 512 KB (Optimus HQ)
* [Ausführlich auf Google+](https://plus.google.com/b/114450218898660299759/114450218898660299759/posts/EUA797D8aYS)

= 1.3.4 =
* Umstellung des Plugins auf die neue Optimus API (cURL only)
* [Ausführlich auf Google+](https://plus.google.com/114450218898660299759/posts/GYrUK4YeXvU)

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

1. Display of the compression rate and how many images have been optimized (circle)
2. Before and after: Original image incl. thumbnails without compression (above) and below with Optimus compressed/optimized
