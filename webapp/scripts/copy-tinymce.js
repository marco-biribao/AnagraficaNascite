// Copia la distribuzione self-hosted di TinyMCE in public/vendor/tinymce,
// cosi' l'editor funziona senza dipendere da una CDN esterna (il server di
// produzione non ha necessariamente accesso a internet dal browser client).
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const sorgente = path.join(__dirname, '..', 'node_modules', 'tinymce');
const destinazione = path.join(__dirname, '..', 'public', 'vendor', 'tinymce');

fs.rmSync(destinazione, { recursive: true, force: true });
fs.cpSync(sorgente, destinazione, { recursive: true });

console.log('TinyMCE copiato in public/vendor/tinymce');
