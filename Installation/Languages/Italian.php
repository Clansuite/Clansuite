<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Installation\Languages;

/**
 * Italian Language for Installation
 *
 * @category        Clansuite
 * @package         Installation
 * @subpackage      Languages
 */
class Italian implements \ArrayAccess
{
    private $language = array();

    // table of strings
    public function __construct()
    {
        // STEP 1 - Language Selection
        $this->language['STEP1_LANGUAGE_SELECTION'] = 'Passo [1] Selezione Lingua';

        $this->language['STEP1_WELCOME'] = 'Benvenuti all\'Installazione di Clansuite.';
        $this->language['STEP1_THANKS_CHOOSING'] = 'Grazie per aver scelto Clansuite!';
        $this->language['STEP1_APPINSTALL_STEPWISE'] = 'Questa applicazione ti guiderà nei passi dell\'installazione.';
        $this->language['STEP1_CHOOSELANGUAGE'] = 'Prego, selezionare una lingua.';

        // STEP 2 - System Check
        $this->language['STEP2_SYSTEMCHECK'] = 'Passo [2] Test di Sistema';

        $this->language['STEP2_IN_GENERAL'] = 'Nel Passo [2] testiamo il tuo webserver per vedere se ha i requisiti.';

        $this->language['STEP2_SYSTEMSETTINGS_REQUIRED'] = 'Qualcuno di questi settaggi di sistema sono richiesti per un corretto funzionamento di Clansuite.';
        $this->language['STEP2_SYSTEMSETTINGS_RECOMMENDED'] = 'Mentre le altre configurazioni sono solo raccomandate per migliorare la sicurezza o le performance.';
        $this->language['STEP2_SYSTEMSETTINGS_TAKEACTION'] = 'Prego, assicurarsi che tutti i test dei requisiti di sistema listati qua sotto sono verdi. Le configurazioni marcate in rosso mostrano dove devi fare qualcosa per sistemare.';
        $this->language['STEP2_SYSTEMSETTINGS_PHPINI'] = 'Cambiamenti al file php.ini devono essere eseguiti ';
        $this->language['STEP2_SYSTEMSETTINGS_CHECK_VALUES'] = 'Il risultato del Test di Sistema:';

        $this->language['STEP2_SYSTEMSETTING_REQUIRED'] = 'Configurazioni Richieste (obbligatorio)';
        $this->language['STEP2_SYSTEMSETTING_RECOMMENDED'] = 'Configurazioni Raccomandate (opzionale)';

        $this->language['STEP2_SETTING'] = 'Configurazioni';
        $this->language['STEP2_SETTING_ACTUAL'] = 'Attuali';
        $this->language['STEP2_SETTING_EXPECTED'] = 'Aspettate';
        $this->language['STEP2_SETTING_STATUS'] = 'Stato';

        $this->language['STEP2_SETTING_EXPECTED_ON'] = 'on';
        $this->language['STEP2_SETTING_EXPECTED_OFF'] = 'off';

        $this->language['STEP2_FIX_REQUIRED_SETTINGS_TOOLTIP'] = 'Per procedere è necessario sistemare le configurazioni richieste.';

        // REQUIRED SETTINGS (in order)
        $this->language['PHP_VERSION'] = 'Versione PHP';
        $this->language['SESSION_FUNCTIONS'] = 'Funzioni della Sessione';
        $this->language['SESSION_AUTO_START'] = 'Autostart Sessione';
        $this->language['EXTENSION_PDO_MYSQL'] = 'Estensione: "pdo_mysql"';
        $this->language['IS_WRITEABLE_TEMP_DIR'] = 'Usabile: Directory Temporanea';
        $this->language['IS_WRITEABLE_CLANSUITE_ROOT'] = 'Posso Scrivere: "/application"';
        $this->language['IS_WRITEABLE_CACHE_DIR'] = 'Posso Scrivere: "/application/cache"';
        $this->language['IS_WRITEABLE_UPLOADS'] = 'Posso Scrivere: "/application/uploads"';
        $this->language['IS_READABLE_CONFIG_TEMPLATE'] = 'Posso Leggere: config-template file';
        $this->language['DATE_TIMEZONE'] = 'Impostazione Timezone: "date.timezone"';

        // RECOMMENDED SETTINGS (in order)
        $this->language['PHP_MEMORY_LIMIT'] = 'Verifica limite memoria PHP';
        $this->language['FILE_UPLOADS'] = 'File Uploads abilitato?';
        $this->language['MAX_UPLOAD_FILESIZE'] = 'Verifica grandezza massima di upload files';
        $this->language['POST_MAX_SIZE'] = 'Verifica grandezza massima delle richieste in post';
        $this->language['ALLOW_URL_FOPEN'] = 'Apertura File Remoti';
        $this->language['ALLOW_URL_INCLUDE'] = 'Inclusione File Remoti';
        $this->language['SAFE_MODE'] = 'SAFE_MODE';
        $this->language['OPEN_BASEDIR'] = 'OPEN_BASEDIR';
        $this->language['MAGIC_QUOTES_GPC'] = 'Magic Quotes GPC';
        $this->language['MAGIC_QUOTES_RUNTIME'] = 'Magic Quotes Runtime';
        $this->language['SHORT_OPEN_TAG'] = 'Short Open Tags';
        $this->language['OUTPUT_BUFFERING'] = 'Output Buffering';
        $this->language['XSLT_PROCESSOR'] = 'XSLT Processor';
        $this->language['EXTENSION_HASH'] = 'Estensione PHP: Hash';
        $this->language['EXTENSION_GETTEXT'] = 'Estensione PHP: Gettext';
        $this->language['EXTENSION_MBSTRING'] = 'Estensione PHP: MBString (Multi-Byte)';
        $this->language['EXTENSION_TOKENIZER'] = 'Estensione PHP: Tokenizer';
        $this->language['EXTENSION_GD'] = 'Estensione PHP: GD';
        $this->language['EXTENSION_XML'] = 'Estensione PHP: XML';
        $this->language['EXTENSION_PCRE'] = 'Estensione PHP: PCRE (Perl Regexp)';
        $this->language['EXTENSION_SIMPLEXML'] = 'Estensione PHP: SimpleXML';
        $this->language['EXTENSION_SUHOSIN'] = 'Estensione PHP: Suhosin';
        $this->language['EXTENSION_SKEIN'] = 'Estensione PHP: Skein';
        $this->language['EXTENSION_GEOIP'] = 'Estensione PHP: GeoIP';
        $this->language['EXTENSION_CURL'] = 'Estensione PHP: cURL';
        $this->language['EXTENSION_SYCK'] = 'Estensione PHP: SYCK';
        $this->language['EXTENSION_APC'] = 'Estensione PHP: APC';
        $this->language['EXTENSION_MEMCACHE'] = 'Estensione PHP: MEMCACHE';
        $this->language['EXTENSION_MCRYPT'] = 'Estensione PHP: MCRYPT';
        $this->language['EXTENSION_CALENDAR'] = 'Estensione PHP: CALENDAR';

        // STEP 3 - License
        $this->language['STEP3_LICENSE'] = 'Passo [3] Licenza GNU/GPL';

        $this->language['STEP3_SENTENCE1'] = 'Rendetevi conto che Clansuite come un insieme di codice è rilasciato sotto licenza GNU / GPL License Version 2 o qualsiasi versione successiva! La licenza GNU / GPL che trovate qui sotto, a sua volta è protetto da copyright dalla Free Software Foundation.';
        $this->language['STEP3_REVIEW_THIRDPARTY'] = 'Leggere prego la lista completa delle licenze open-source sul software incluso in Clansuite dopo quando finita l\'installazione. Possono essere trovate nel THIRD-PARTY-LIBRARIES.txt file della cartella "/doc".';
        $this->language['STEP3_REVIEW_CLANSUITE'] = 'Leggere prego i Termini di Licenza prima di installare Clansuite:';
        $this->language['STEP3_MUST_AGREE'] = 'Devi accettare la Licenza GNU/GPL per installare Clansuite.';
        $this->language['STEP3_CHECKBOX'] = 'Accetto e confermo che Clansuite è rilasciato sotto la Licenza GNU/GPL!';

        // STEP 4 - Database
        $this->language['STEP4_DATABASE'] = 'Passo [4] Database';

        $this->language['STEP4_SENTENCE1'] = 'Nel Passo [4] tu fornirai le Informazioni di Accesso al Database e noi proveremo a connetterci e creare qualche tabella di contenuti e base di Clansuite.';
        $this->language['STEP4_SENTENCE2'] = 'Fornire prego il nome utente e la password per connettersi al server qui.';
        $this->language['STEP4_SENTENCE3'] = 'Se l\'account ha i permessi di creare i database, allora noi creeremo il database per te; altrimenti, devi dare il nome di un database che già esiste.';

        $this->language['STEP4_SENTENCE4'] = 'Tabelle e voci create.';
        $this->language['STEP4_SENTENCE5'] = 'Importare Database/Tabelle di un altro CMS?';

        $this->language['HOST'] = 'Hostname del Database';
        $this->language['DRIVER'] = 'Driver del Database';
        $this->language['NAME'] = 'Nome del Database';
        $this->language['CREATE_DATABASE'] = 'Creare un Database?';
        $this->language['USERNAME'] = 'Nome Utente del Database';
        $this->language['PASSWORD'] = 'Password del Database';
        $this->language['PREFIX'] = 'Prefisso delle Tabelle';

        $this->language['ERROR_NO_DB_CONNECT'] = 'La connessione al Database non può essere stabilita.';
        $this->language['ERROR_WHILE_CREATING_DATABASE'] = 'Il Database non può essere creato.';
        $this->language['ERROR_FILL_OUT_ALL_FIELDS'] = 'Compilare prego tutti i campi!';

        $this->language['HOST_TOOLTIP'] = 'Inserire l\'Hostname del tuo Database. E\' spesso 127.0.0.1 o semplicemente localhost.';
        $this->language['DRIVER_TOOLTIP'] = 'Inserire il tipo di Database.';
        $this->language['NAME_TOOLTIP'] = 'Inserisci il nome del tuo Database.';
        $this->language['CREATEDB_TOOLTIP'] = 'Se l\'utente del Database inserito, ha il permesso di creare Database, è consigliato sceglierne uno nuovo.';
        $this->language['USERNAME_TOOLTIP'] = 'Inserisci il Nome Utente con i permessi di scrittura al tuo Database.';
        $this->language['PASSWORD_TOOLTIP'] = 'Adesso inserisci la password per questo utente.';
        $this->language['PREFIX_TOOLTIP'] = 'Si potrebbe inserire un prefisso per le tabelle del database di Clansuite.';
        $this->language['PREFIX_TOOLTIP'] .= 'Prefissare le tabelle ti permette evitare la collisione dei nomi quando si usa un solo Database.';

        // STEP 5 - Configuration
        $this->language['STEP5_CONFIG'] = 'Passo [5] Configurazioni';

        $this->language['STEP5_LEGEND'] = 'Configurazioni';

        $this->language['STEP5_SENTENCE1'] = 'Inserire prego la configurazione base del tuo Sito Internet di Clansuite.';
        $this->language['STEP5_SENTENCE2'] = 'Quando l\'installazione è completa, sarai in grado di configurare più dettagli dal pannello amministrativo (ACP).';

        $this->language['STEP5_CONFIG_SITENAME'] = 'Nome Sito Web';
        $this->language['STEP5_CONFIG_EMAILFROM'] = 'Indirizzo Email del Sito Web';
        $this->language['STEP5_CONFIG_USERACCOUNT_ENCRYPTION'] = 'Criptaggio';
        $this->language['STEP5_CONFIG_GMTOFFSET'] = 'Timezone';

        $this->language['STEP5_SITENAME_TOOLTIP'] = 'Dare prego un nome al tuo nuovo Sito Web. Il nome sarà mostrato come titolo nel browser.';
        $this->language['STEP5_SYSTEM_EMAIL_TOOLTIP'] = 'Inserire prego un Indirizzo Email. Clansuite userà poi questo Indirizzo Email per messaggiare gli utente del tuo Sito Web.';
        $this->language['STEP5_ACCOUNT_CRYPT_TOOLTIP'] = 'Selezionare prego il tipo di misure di sicurezza per le password degli account utente. Se il tuo Database cade in mani sbagliate, le password degli utenti non sono state salvate in testo piatto.';
        $this->language['STEP5_GMTOFFSET_TOOLTIP'] = 'Selezionare prego il proprio timezone. L\'impostazione del timezone è essenziale per tutte le date e il calcolo del tempo.';

        // STEP 6 - Create Administrator
        $this->language['STEP6_ADMINUSER'] = 'Passo [6] Creare l\'Amministratore';

        $this->language['STEP6_LEGEND'] = 'Crea l\'Account Amministratore';

        $this->language['STEP6_SENTENCE1'] = 'Nel Passo [6] noi creiamo un Account Utente con i Dati Utente che tu fornirai.';
        $this->language['STEP6_SENTENCE2'] = 'Noi daremo a questo account i Permessi di Amministratore, questo significa che sarai in grado di accedere e impostare tutte le configurazioni con esso.';
        $this->language['STEP6_SENTENCE3'] = 'Inserire prego il Nome e la Password come anche l\'E-Mail e la Lingua dell\'Account Amministratore.';

        $this->language['STEP6_ADMIN_NAME']     = 'Nome Amministratore';
        $this->language['STEP6_ADMIN_PASSWORD'] = 'Password Amministratore';
        $this->language['STEP6_ADMIN_LANGUAGE'] = 'Lingua';
        $this->language['STEP6_ADMIN_EMAIL']    = 'Indirizzo Email';

        $this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'] = 'ERRORE -  Account di Admin non creato.';

        // STEP 7 - Finish
        $this->language['STEP7_FINISH'] = 'Passo [7] Fine';

        $this->language['STEP7_SENTENCE1'] = 'Finito! Congratulazioni - Hai completato con successo l\'installazione di Clansuite.';
        $this->language['STEP7_SENTENCE2'] = 'Il Team degli Sviluppatori spera che esplorerai e userai con piacere Clansuite.';
        $this->language['STEP7_SENTENCE3'] = 'Sotto troverete i collegamenti alla pagina principale del Sito Web, all\'Admin Contro Center (ACC) e i dati di accesso del tuo account utente.';
        $this->language['STEP7_SENTENCE4'] = 'Visita il tuo nuovo';
        $this->language['STEP7_SENTENCE5'] = 'Sito Web Clansuite';
        $this->language['STEP7_SENTENCE6'] = 'o il';

        $this->language['STEP7_SENTENCE8'] = 'Per aiuto o informazioni riguardo l\'uso e la configurazione del software Clansuite sentiti libero di visitare il ';
        $this->language['STEP7_SENTENCE9'] = 'Manuale Utente';
        $this->language['STEP7_SENTENCE10'] = 'Avvertenze sulla Sicurezza';
        $this->language['STEP7_SENTENCE11'] = 'Non dimenticarti prego di rinominare o rimuovere la cartella "/installation" per ragioni di sicurezza.';
        $this->language['STEP7_SENTENCE12'] = 'Cancella la sotto cartertella "/installation" immediatamente!';

        /* @todo http://trac.clansuite.com/ticket/7
        $this->language['STEP7_SUPPORT_ENTRY_LEGEND'] = '';
        $this->language['STEP7_SUPPORT_ENTRY_1'] = '';
        $this->language['STEP7_SUPPORT_ENTRY_2'] = '';
        $this->language['STEP7_SUPPORT_ENTRY_3'] = '';
        $this->language['STEP7_SUPPORT_ENTRY_4'] = '';
        */

        // GLOBAL

        // Buttons
        $this->language['NEXTSTEP'] = 'Avanti &gt;&gt;';
        $this->language['BACKSTEP'] = '&lt;&lt; Indietro';

        // Help Text for Buttons
        $this->language['CLICK_NEXT_TO_PROCEED'] = 'Clicca il Tasto ['. $this->language['NEXTSTEP'] .'] per procedere al prossimo Passo dell\'installazione.';
        $this->language['CLICK_BACK_TO_RETURN'] = 'Clicca il Tasto ['. $this->language['BACKSTEP'] .'] per ritornare al precedente.';

        // Right Side Menu
        $this->language['INSTALL_PROGRESS'] = 'Progresso Installazione';
        $this->language['SELECT_LANGUAGE'] = 'Selezionare prego una Lingua.';
        $this->language['COMPLETED'] = 'COMPLETATO';
        $this->language['CHANGE_LANGUAGE'] = 'Cambia la Lingua';
        $this->language['SHORTCUTS'] = 'Collegamenti';
        $this->language['LIVESUPPORT'] = 'Serve Aiuto?';
        $this->language['GETLIVESUPPORT_STATIC'] = 'Live Support (Inizia Chat.)';

        // Left Side Menu
        $this->language['MENU_HEADING'] = 'Passi installazione';
        $this->language['MENUSTEP1'] = '[1] Selezione Lingua';
        $this->language['MENUSTEP2'] = '[2] Test di Sistema';
        $this->language['MENUSTEP3'] = '[3] Licenza GNU/GPL';
        $this->language['MENUSTEP4'] = '[4] Database';
        $this->language['MENUSTEP5'] = '[5] Configurazioni';
        $this->language['MENUSTEP6'] = '[6] Creare l\'Amministratore';
        $this->language['MENUSTEP7'] = '[7] Fine';

        ###

        $this->language['HELP'] = 'Aiuto';
        $this->language['LICENSE'] = 'Licenza';
    }

    /**
     * Implementation of SPL ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->language[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->language[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    // hmm? why should language be unset?
    public function offsetUnset($offset)
    {
        unset($this->language[$offset]);

        return true;
    }
}
