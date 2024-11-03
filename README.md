# Rickroll Generator für YOURLS

Erstelle Rickroll-Links mit benutzerdefinierten Vorschaumetadaten in YOURLS.

## Beschreibung

Der **Rickroll Generator** ist ein Plugin für [YOURLS](https://yourls.org/), einen selbst gehosteten URL-Verkürzungsdienst. Mit diesem Plugin kannst du spezielle Rickroll-Links erstellen, die beim Teilen auf Plattformen wie Discord, Facebook oder Twitter eine benutzerdefinierte Vorschau anzeigen. Die Vorschau zeigt einen von dir festgelegten Titel, eine Beschreibung und ein Bild, um Nutzer zum Klicken zu verleiten, bevor sie dann zum klassischen Rickroll-Video weitergeleitet werden.

## Funktionen

- **Benutzerdefinierte Vorschaumetadaten**: Lege einen eigenen Titel, eine Beschreibung und ein Bild für die Link-Vorschau fest.
- **Nahtlose Integration**: Funktioniert mit deiner bestehenden YOURLS-Installation.
- **Einfach zu bedienen**: Einfache Oberfläche zum Erstellen von Rickroll-Links.
- **Schnelle Weiterleitung**: Der Link leitet nach kurzer Verzögerung zur Ziel-URL weiter, sodass die Vorschau auf sozialen Plattformen angezeigt wird.

## Installation

1. **Plugin herunterladen**:

   - Lade das Plugin aus dem [GitHub-Repository](https://github.com/bnfone/yourls-rickroll-generator) herunter oder klone es.

2. **In YOURLS-Plugins-Verzeichnis hochladen**:

   - Lade den Plugin-Ordner `yourls-rickroll-generator` in das Verzeichnis `user/plugins` deiner YOURLS-Installation hoch.

3. **Plugin aktivieren**:

   - Melde dich in deinem YOURLS-Adminbereich an.
   - Gehe zur Seite "Manage Plugins" (Plugins verwalten).
   - Aktiviere das Plugin "Rickroll Generator".

## Nutzung

1. **Rickroll Generator aufrufen**:

   - Klicke im YOURLS-Adminbereich im Menü auf "Rickroll Generator".

2. **Rickroll-Link erstellen**:

   - **Ziel-URL**: Gib die URL ein, zu der weitergeleitet werden soll (z. B. das Rickroll-Video auf YouTube).
   - **Benutzerdefiniertes Keyword (optional)**: Wenn du ein bestimmtes Keyword für deine Kurz-URL möchtest, gib es hier ein.
   - **Vorschau-Titel**: Gib den Titel ein, der in der Link-Vorschau angezeigt wird.
   - **Vorschau-Beschreibung**: Gib die Beschreibung für die Link-Vorschau ein.
   - **Vorschau-Bild-URL**: Gib die URL des Bildes ein, das in der Vorschau angezeigt werden soll.

3. **Link generieren**:

   - Klicke auf die Schaltfläche "Link erstellen".
   - Das Plugin erstellt eine neue Kurz-URL mit den angegebenen Vorschaumetadaten.

4. **Link teilen**:

   - Teile die generierte Kurz-URL auf sozialen Medien oder in Messaging-Apps.
   - Die benutzerdefinierte Vorschau wird angezeigt und verleitet Nutzer zum Klicken.

## Funktionsweise

- **Speicherung der Metadaten**: Das Plugin speichert die benutzerdefinierten Vorschaumetadaten (Titel, Beschreibung, Bild) als JSON-kodierten String im `title`-Feld der YOURLS-Datenbank.

- **Anzeigen der Vorschau**: Wenn die Kurz-URL aufgerufen wird, fängt das Plugin den Weiterleitungsprozess ab und zeigt eine benutzerdefinierte Vorschauseite an, die die Open Graph Meta-Tags mit deinen angegebenen Metadaten enthält.

- **Automatische Weiterleitung**: Nach einer kurzen Verzögerung (standardmäßig 1,5 Sekunden) leitet die Seite den Nutzer mithilfe von JavaScript automatisch zur Ziel-URL weiter.

- **Kompatibilität mit sozialen Plattformen**: Durch Setzen des HTTP-Status auf 200 und Bereitstellen der Open Graph Meta-Tags können soziale Medien beim Teilen des Links die benutzerdefinierte Vorschau anzeigen.

## Konfiguration

- **Weiterleitungsverzögerung**:

  - Die Weiterleitungsverzögerung ist standardmäßig auf 1,5 Sekunden eingestellt.
  - Du kannst die Verzögerung anpassen, indem du den Wert in der JavaScript-`setTimeout`-Funktion in der Funktion `rickroll_show_preview` im Plugin-Code änderst.

  ```javascript
  setTimeout(function () {
      window.location.href = "<?php echo htmlspecialchars($link['url']); ?>";
  }, 1500); // Passe die Verzögerung in Millisekunden an
  ```

## Anforderungen

- **YOURLS**: Dieses Plugin erfordert YOURLS Version 1.7 oder höher.

## Fehlerbehebung

- **Probleme bei der Plugin-Aktivierung**:

  - Solltest du Fehler bei der Aktivierung des Plugins erhalten, stelle sicher, dass deine YOURLS-Installation die Versionsanforderungen erfüllt.
  - Überprüfe auf Konflikte mit anderen Plugins, die die URL-Weiterleitung oder Metadatenverarbeitung beeinflussen könnten.

- **Vorschau wird auf sozialen Plattformen nicht angezeigt**:

  - Manche Plattformen cachen die Vorschau-Daten. Nutze deren Debugging-Tools (z. B. den Facebook Sharing Debugger), um den Cache zu aktualisieren.
  - Stelle sicher, dass die Weiterleitungsverzögerung ausreichend ist, damit die Plattform die Metadaten auslesen kann (1–2 Sekunden werden empfohlen).

## Lizenz

Dieses Plugin ist Open-Source und unter der MIT-Lizenz veröffentlicht. Siehe die [LICENSE](https://github.com/bnfone/yourls-rickroll-generator/blob/main/LICENSE)-Datei für Details.

## Autor

- **Benedikt Fischer**
- Website: [https://bnfone.com/](https://bnfone.com/)

## Mitwirken

Beiträge sind willkommen! Wenn du einen Fehler findest oder einen Feature-Wunsch hast, öffne bitte ein Issue im [GitHub-Repository](https://github.com/bnfone/yourls-rickroll-generator).

## Danksagungen

- Dank an die YOURLS-Community für die Bereitstellung einer erweiterbaren Plattform für URL-Verkürzungen.
- Dieses Plugin wurde inspiriert von dem Wunsch, ein wenig Spaß und Überraschung in geteilte Links zu bringen.

---

**Viel Spaß beim Rickrollen!**