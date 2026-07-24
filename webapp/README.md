# Anagrafica Nascite

Applicazione Laravel per la gestione delle dichiarazioni di nascita (AUSL Umbria 2, Presidio Ospedaliero di Foligno) — sostituisce il vecchio archivio EpiInfo/Access conservato nella cartella `dichiarazioni di nascita/` a livello di repository.

Funzionalità principali:
- Inserimento, ricerca, modifica ed esclusione/ripristino delle dichiarazioni di nascita (modelli A/A1/B/B1/C/C1/D/D1 e varianti madre, secondo il DPR 396/2000)
- Stampa PDF dei moduli, con template modificabili dagli utenti finali tramite un editor WYSIWYG (senza bisogno di supporto tecnico ad ogni cambio normativo)
- Autenticazione su Active Directory (LDAP), con un account locale di emergenza se il dominio non è raggiungibile
- Gestione utenti e ruoli (i ruoli applicativi sono locali, non derivati dai gruppi AD)
- Registro delle modifiche (audit log) sulle dichiarazioni

## Requisiti

- PHP 8.3 con le estensioni: `mbstring`, `xml`, `curl`, `mysql` (pdo_mysql), `zip`, `bcmath`, `gd`, `intl`, `ldap`
- Composer
- Node.js e npm
- MySQL o MariaDB

Su Ubuntu/Debian:
```bash
sudo apt-get update
sudo apt-get install -y php8.3 php8.3-cli php8.3-common php8.3-mbstring php8.3-xml \
    php8.3-curl php8.3-mysql php8.3-zip php8.3-bcmath php8.3-gd php8.3-intl php8.3-ldap \
    composer mysql-server nodejs npm
```

## Setup ambiente di sviluppo locale

1. **Clonare il repository**
   ```bash
   git clone https://github.com/marco-biribao/AnagraficaNascite.git
   cd AnagraficaNascite/webapp
   ```

2. **Creare il database locale**
   ```bash
   sudo mysql -e "
   CREATE DATABASE anagrafica_nascite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'anagrafica'@'localhost' IDENTIFIED BY 'scegli-una-password';
   GRANT ALL PRIVILEGES ON anagrafica_nascite.* TO 'anagrafica'@'localhost';
   FLUSH PRIVILEGES;"
   ```

3. **Configurare l'applicazione**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Aprire `.env` e impostare `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` con i valori del punto precedente.

   Per lo sviluppo locale i parametri `LDAP_*` possono restare vuoti: in tal caso funziona solo l'account locale creato dal seeder (`ADMIN_USERNAME`/`ADMIN_PASSWORD` in `.env`, di default `admin.locale`).

4. **Installare le dipendenze e compilare gli asset**
   ```bash
   composer install
   npm install
   npm run build
   ```
   (`npm run build` compila anche gli asset di TinyMCE, l'editor dei template dei report, in `public/vendor/tinymce` — non è versionato in Git, va rigenerato ad ogni checkout pulito.)

5. **Creare le tabelle e i dati di riferimento**
   ```bash
   php artisan migrate --seed
   ```
   Popola modelli di dichiarazione, dichiaranti, ruoli, template di report di base e l'account amministratore locale.

6. **Avviare il server di sviluppo**
   ```bash
   php artisan serve
   ```
   L'app è raggiungibile su `http://127.0.0.1:8000`.

## Contribuire modifiche

Ogni persona deve generare il proprio Personal Access Token GitHub (Settings → Developer settings → Personal access tokens, scope `repo`) per poter fare `git push` — i token non sono condivisibili tra account.

## Deploy in produzione

Il deploy sul server interno (build locale con dipendenze di produzione, trasferimento via rsync/tar, configurazione Apache/HTTPS, LDAP verso Active Directory) segue una procedura separata, non descritta qui per non esporre dettagli infrastrutturali nel repository. Chi ha già effettuato un deploy può guidarti nei passaggi.
