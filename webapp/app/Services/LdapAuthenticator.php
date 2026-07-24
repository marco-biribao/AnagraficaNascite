<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use LdapRecord\Connection;
use LdapRecord\LdapRecordException;

/**
 * Verifica le credenziali di un utente direttamente su Active Directory
 * (bind LDAP), senza passare dal provider Eloquent standard di Laravel:
 * l'esito viene poi usato da AuthenticatedSessionController per creare o
 * aggiornare l'utente locale corrispondente. I ruoli applicativi NON
 * vengono dedotti dai gruppi AD: restano una tabella locale gestita dagli
 * amministratori (vedi UtenteController).
 */
class LdapAuthenticator
{
    /**
     * @return array{dn: string, guid: ?string, nome: string, email: ?string}|null
     */
    public function autentica(string $username, string $password): ?array
    {
        $config = config('ldap.connections.'.config('ldap.default'));

        $connection = new Connection($config);

        try {
            $connection->connect();
        } catch (LdapRecordException $e) {
            Log::warning('LDAP: impossibile connettersi al controller di dominio.', ['errore' => $e->getMessage()]);

            return null;
        }

        $attributoUsername = config('ldap.attributes.username');
        $attributoGuid = config('ldap.attributes.guid');

        $voce = $connection->query()
            ->in($config['base_dn'])
            ->select(['*', $attributoGuid])
            ->where($attributoUsername, '=', $username)
            ->first();

        if (! $voce) {
            return null;
        }

        $dn = is_array($voce['dn']) ? $voce['dn'][0] : $voce['dn'];

        try {
            if (! $connection->auth()->attempt($dn, $password)) {
                return null;
            }
        } catch (LdapRecordException $e) {
            Log::warning('LDAP: bind fallito.', ['username' => $username, 'errore' => $e->getMessage()]);

            return null;
        }

        return [
            'dn' => $dn,
            'guid' => $this->formatGuid($this->primoValore($voce, $attributoGuid)),
            'nome' => $this->primoValore($voce, 'displayname') ?? $this->primoValore($voce, 'cn') ?? $username,
            'email' => $this->primoValore($voce, 'mail'),
        ];
    }

    private function primoValore(array $voce, string $attributo): ?string
    {
        $valore = $voce[$attributo] ?? null;

        if (is_array($valore)) {
            return $valore[0] ?? null;
        }

        return $valore;
    }

    /**
     * Su Active Directory "objectGUID" arriva come 16 byte binari grezzi,
     * non come stringa: va convertito nella notazione standard
     * "xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" prima di poterlo salvare in
     * una colonna di testo. Altre directory (es. OpenLDAP con entryUUID,
     * usato in sviluppo) restituiscono gia' una stringa leggibile e non
     * vanno toccate.
     */
    private function formatGuid(?string $valore): ?string
    {
        if ($valore === null || strlen($valore) !== 16 || preg_match('//u', $valore) === 1) {
            return $valore;
        }

        $esadecimale = bin2hex($valore);

        return sprintf(
            '%s%s%s%s-%s%s-%s%s-%s-%s',
            substr($esadecimale, 6, 2), substr($esadecimale, 4, 2), substr($esadecimale, 2, 2), substr($esadecimale, 0, 2),
            substr($esadecimale, 10, 2), substr($esadecimale, 8, 2),
            substr($esadecimale, 14, 2), substr($esadecimale, 12, 2),
            substr($esadecimale, 16, 4),
            substr($esadecimale, 20, 12)
        );
    }
}
