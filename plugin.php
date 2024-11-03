<?php
/*
Plugin Name: Rickroll Generator
Plugin URI: https://github.com/bnfone/yourls-rickroll-generator
Description: Erstelle Rickroll-Links mit benutzerdefinierten Vorschaumetadaten
Version: 1.1
Author: Benedikt Fischer
Author URI: https://bnfone.com/
*/

// Direkter Aufruf verhindern
if (!defined('YOURLS_ABSPATH')) die();

// Nicht ausführen, wenn das Plugin deinstalliert wird
if (defined('YOURLS_UNINSTALL_PLUGIN')) {
    return;
}

// Fehlermeldungen für Debugging aktivieren
if (defined('YOURLS_DEBUG') && YOURLS_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Plugin-Admin-Seite hinzufügen
yourls_add_action('plugins_loaded', 'rickroll_init');
function rickroll_init()
{
    yourls_register_plugin_page('rickroll', 'Rickroll Generator', 'rickroll_display_page');
    yourls_register_plugin_page('rickroll_links', 'Rickroll Links', 'rickroll_display_links_page');
}

// Anzeige der Plugin-Admin-Seite
function rickroll_display_page()
{
    // Überprüfen, ob das Formular abgesendet wurde
    if (isset($_POST['rickroll_url'])) {
        try {
            // Nonce überprüfen
            yourls_verify_nonce('rickroll');

            // Benutzereingaben validieren und sanitizen
            $url = yourls_sanitize_url($_POST['rickroll_url']);
            $keyword = isset($_POST['keyword']) ? yourls_sanitize_keyword($_POST['keyword']) : '';
            $title = yourls_esc_html($_POST['title']);
            $description = yourls_esc_html($_POST['description']);
            $image = yourls_sanitize_url($_POST['image']);

            // Neuen Kurzlink erstellen und Metadaten speichern
            $shorturl = rickroll_create_link($url, $keyword, $title, $description, $image);

            if ($shorturl) {
                echo '<p>Rickroll-Link erstellt: <a href="' . htmlspecialchars($shorturl['shorturl']) . '">' . htmlspecialchars($shorturl['shorturl']) . '</a></p>';
            } else {
                echo '<p>Fehler: Konnte den Kurzlink nicht erstellen.</p>';
            }
        } catch (Exception $e) {
            echo '<p>Fehler: ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
    }

    // Formular anzeigen
    ?>
    <h2>Rickroll-Link erstellen</h2>
    <form method="post">
        <p>
            <label>Ziel-URL</label><br/>
            <input type="url" name="rickroll_url" required 
                   value="https://www.youtube.com/watch?v=dQw4w9WgXcQ" 
                   placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ"/>
        </p>
        <p>
            <label>Benutzerdefiniertes Keyword (optional)</label><br/>
            <input type="text" name="keyword"/>
        </p>
        <p>
            <label>Vorschau-Titel</label><br/>
            <input type="text" name="title" required/>
        </p>
        <p>
            <label>Vorschau-Beschreibung</label><br/>
            <textarea name="description" required></textarea>
        </p>
        <p>
            <label>Vorschau-Bild-URL</label><br/>
            <input type="url" name="image" required/>
        </p>
        <?php yourls_nonce_field('rickroll'); ?>
        <p><input type="submit" value="Link erstellen" class="button" /></p>
    </form>
    <?php
}

// Funktion zum Erstellen des Kurzlinks und Speichern der Metadaten
function rickroll_create_link($url, $keyword, $title, $description, $image)
{
    // Metadaten als JSON kodieren
    $meta = json_encode(array(
        'title' => $title,
        'description' => $description,
        'image' => $image
    ));

    // Kurzlink erstellen und Metadaten speichern
    $return = yourls_add_new_link($url, $keyword, $meta);

    if ($return && isset($return['status']) && $return['status'] == 'success') {
        return $return;
    }

    return false;
}

// Abfangen der Weiterleitung, um die Vorschauseite anzuzeigen
yourls_add_action('pre_redirect', 'rickroll_show_preview');

function rickroll_show_preview($args)
{
    // Verhindern, dass die Funktion im Admin-Bereich oder während der Plugin-Deaktivierung ausgeführt wird
    if (yourls_is_admin()) {
        return;
    }

    // Überprüfen, ob die benötigten Funktionen vorhanden sind
    if (!function_exists('yourls_get_keyword_infos')) {
        return;
    }

    // Globale Variable $keyword abrufen
    global $keyword;

    if (!isset($keyword) || empty($keyword)) {
        return; // Weiter mit der normalen Weiterleitung
    }

    // Keyword sanitizen
    $keyword = yourls_sanitize_keyword($keyword);

    // Linkdaten abrufen
    $link = yourls_get_keyword_infos($keyword);

    if (!$link) {
        return; // Weiter mit der normalen Weiterleitung
    }

    // Metadaten aus dem Feld 'title' extrahieren
    $meta_json = $link['title'];
    $meta = json_decode($meta_json);

    if (!$meta || !isset($meta->title) || !isset($meta->description) || !isset($meta->image)) {
        return; // Weiter mit der normalen Weiterleitung
    }

    // Vorschauseite mit Metadaten anzeigen
    // HTTP-Status auf 200 OK setzen
    yourls_status_header(200);

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title><?php echo htmlspecialchars($meta->title); ?></title>
        <meta property="og:title" content="<?php echo htmlspecialchars($meta->title); ?>" />
        <meta property="og:description" content="<?php echo htmlspecialchars($meta->description); ?>" />
        <meta property="og:image" content="<?php echo htmlspecialchars($meta->image); ?>" />
        <meta charset="UTF-8">
        <style>
            body { font-family: sans-serif; max-width: 600px; margin: 40px auto; text-align: center; }
        </style>
    </head>
    <body>
        <h1><?php echo htmlspecialchars($meta->title); ?></h1>
        <p><?php echo htmlspecialchars($meta->description); ?></p>
        <?php if (!empty($meta->image)): ?>
            <img src="<?php echo htmlspecialchars($meta->image); ?>" alt="Vorschaubild" style="max-width: 100%;">
        <?php endif; ?>
        <p>Sie werden in wenigen Sekunden weitergeleitet...</p>

        <!-- JavaScript-Weiterleitung mit verkürzter Zeit -->
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href = "<?php echo htmlspecialchars($link['url']); ?>";
            }, 1500); // Weiterleitung nach 1,5 Sekunden
        </script>

        <?php if (defined('YOURLS_DEBUG') && YOURLS_DEBUG): ?>
            <pre>
    Debug Info:
    Keyword: <?php echo htmlspecialchars($keyword); ?>
    Meta: <?php print_r($meta); ?>
            </pre>
        <?php endif; ?>
    </body>
    </html>
    <?php
    exit(); // Normale Weiterleitung stoppen
}

// Funktion zum Abrufen der Rickroll-Links
function get_rickroll_links()
{
    $table_url = YOURLS_DB_TABLE_URL;

    $sql = "SELECT * FROM `$table_url`";
    $links = yourls_get_db()->fetchObjects($sql);

    $rickroll_links = array();

    foreach ($links as $link) {
        $meta = json_decode($link->title);
        if ($meta && isset($meta->title) && isset($meta->description) && isset($meta->image)) {
            // Dies ist ein Rickroll-Link
            $rickroll_links[] = $link;
        }
    }

    return $rickroll_links;
}

// Anzeige der Rickroll-Links-Seite
function rickroll_display_links_page()
{
    // Wenn ein Link gelöscht werden soll
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['keyword'])) {
        yourls_verify_nonce('rickroll_delete_link');
        $keyword = yourls_sanitize_keyword($_GET['keyword']);
        $result = yourls_delete_link_by_keyword($keyword);
        if ($result) {
            echo '<p>Link mit dem Keyword ' . htmlspecialchars($keyword) . ' wurde gelöscht.</p>';
        } else {
            echo '<p>Fehler beim Löschen des Links mit dem Keyword ' . htmlspecialchars($keyword) . '.</p>';
        }
    }

    // Rickroll-Links abrufen
    $links = get_rickroll_links();

    // Zähler
    $total_links = count($links);
    $total_clicks = 0;

    echo '<h2>Rickroll-Links</h2>';
    echo '<p>Anzahl der Rickroll-Links: ' . $total_links . '</p>';

    if ($total_links > 0) {
        echo '<table border="1" cellpadding="5" cellspacing="0">';
        echo '<tr><th>Keyword</th><th>URL</th><th>Klicks</th><th>Aktion</th></tr>';

        foreach ($links as $link) {
            $keyword = $link->keyword;
            $url = $link->url;
            $clicks = $link->clicks;
            $total_clicks += $clicks;
            $delete_link = yourls_nonce_url('rickroll_delete_link', yourls_admin_url('plugins.php?page=rickroll_links&action=delete&keyword=' . $keyword));

            echo '<tr>';
            echo '<td>' . htmlspecialchars($keyword) . '</td>';
            echo '<td><a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($url) . '</a></td>';
            echo '<td>' . $clicks . '</td>';
            echo '<td><a href="' . $delete_link . '" onclick="return confirm(\'Möchten Sie diesen Link wirklich löschen?\')">Löschen</a></td>';
            echo '</tr>';
        }

        echo '</table>';

        echo '<p>Gesamte Rickroll-Klicks: ' . $total_clicks . '</p>';

    } else {
        echo '<p>Es wurden noch keine Rickroll-Links erstellt.</p>';
    }
}
?>
