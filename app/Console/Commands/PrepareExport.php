<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Everything the old server needs before the new app can copy from it, in one run (as forge,
 * once the code is up to date; the new app's app:provision brings it, as a git bundle):
 *
 *   1. checks the avatars and thumbnails for files that aren't images (the podcast-form hole)
 *   2. sets LEGACY_EXPORT_KEY and LEGACY_EXPORT_HOST in .env (a backup of .env is kept)
 *   3. creates storage/framework/iaw-busy
 *   4. caches the config and clears the route cache
 *   5. writes storage/app/iaw/install-legacy-host.sh, which (run with sudo) sets up
 *      legacy.itsallwidgets.com: its nginx site with the key gate, its certificate, and the
 *      PHP-FPM reload
 *
 * Safe to run again. The keys come from the new app: app:provision sends them on standard input
 * (--stdin), never on a command line or in any output.
 */
class PrepareExport extends Command
{
    protected $signature = 'iaw:prepare-export
        {--checked : The abuse check found files, and every secret in .env has been rotated since}
        {--stdin : Read the two keys from standard input (one per line), as the new app\'s app:provision sends them}';

    protected $description = 'Prepare this server for the new app: export keys, busy folder, caches, and the legacy. host installer';

    const HOST = 'legacy.itsallwidgets.com';

    public function handle()
    {
        if (! file_exists(base_path('routes/export.php'))) {
            $this->error('The export route isn\'t here yet: bring the code up to date first (the new app\'s app:provision does it).');

            return 1;
        }

        if (! $this->abuseCheck()) {
            return 1;
        }

        if ($this->option('stdin')) {
            // Sent by app:provision over SSH: never on a command line, never printed.
            $exportKey = trim((string) fgets(STDIN));
            $importKey = trim((string) fgets(STDIN));

            if (! preg_match('/^[0-9a-f]{64}$/', $exportKey) || ! preg_match('/^\S{16,}$/', $importKey)) {
                $this->error('The keys on standard input don\'t look right.');

                return 1;
            }
        } else {
            $exportKey = $this->askKey('LEGACY_EXPORT_KEY (64 hex characters, from the new app)', '/^[0-9a-f]{64}$/');
            $importKey = $this->askKey('LEGACY_IMPORT_KEY (from the new app)', '/^\S{16,}$/');
        }

        $this->setEnv(['LEGACY_EXPORT_KEY' => $exportKey, 'LEGACY_EXPORT_HOST' => self::HOST]);
        $this->info('✓ .env has LEGACY_EXPORT_KEY and LEGACY_EXPORT_HOST.');

        $busy = storage_path('framework/iaw-busy');

        if (! is_dir($busy)) {
            mkdir($busy, 0775, true);
        }

        if (! is_writable($busy)) {
            $this->error($busy.' isn\'t writable by this user. Run this command as the PHP-FPM user (sudo -u forge).');

            return 1;
        }

        $this->info('✓ storage/framework/iaw-busy is ready.');

        $this->call('config:cache');
        $this->call('route:clear');
        // The forge user may reload PHP-FPM without a password (Forge's sudoers rule).
        exec('sudo -n /usr/sbin/service php7.3-fpm reload 2>&1', $output, $status);
        $this->line($status === 0 ? '✓ PHP-FPM reloaded: the export route is live.' : 'Reload PHP-FPM yourself: sudo service php7.3-fpm reload');

        $installer = $this->writeInstaller($importKey);

        $this->line('');
        $this->info('Done. One more command, with sudo (it asks for your password):');
        $this->line('');
        $this->line('    sudo bash '.$installer);
        $this->line('');
        $this->line('It sets up '.self::HOST.' (its DNS record must point at this server first), gets its');
        $this->line('certificate and reloads nginx and PHP-FPM. The new app then starts its first copy by itself.');

        return 0;
    }

    /**
     * The podcast form used to save any file as an "avatar": anything here that isn't an image
     * means someone used it, and every secret in .env must be rotated first.
     */
    private function abuseCheck()
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $found = [];

        foreach (['avatars', 'thumbnails'] as $folder) {
            $directory = public_path($folder);

            if (! is_dir($directory)) {
                continue;
            }

            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)) as $file) {
                $type = (string) $finfo->file($file->getPathname());

                // The folders' own .gitignore (tracked) and other dotfiles aren't uploads.
                if ($file->isFile() && strpos($file->getFilename(), '.') !== 0 && strpos($type, 'image/') !== 0) {
                    $found[] = $folder.'/'.$file->getFilename().' ('.$type.')';
                }
            }
        }

        if ($found === []) {
            $this->info('✓ Abuse check: every avatar and thumbnail is an image.');

            return true;
        }

        $this->warn('These aren\'t images:');

        foreach (array_slice($found, 0, 50) as $line) {
            $this->line('  '.$line);
        }

        if ($this->option('checked')) {
            $this->warn('Carrying on (--checked: the secrets have been rotated).');

            return true;
        }

        $this->error('Rotate every secret in .env (database, Google, Twitter/X, YouTube, mail), then run this again with --checked.');

        return false;
    }

    private function askKey($label, $pattern)
    {
        while (true) {
            $value = trim((string) $this->secret($label));

            if (preg_match($pattern, $value)) {
                return $value;
            }

            $this->error('That doesn\'t look right. Paste it again.');
        }
    }

    /**
     * Sets or replaces lines in .env, keeping a dated backup of the file first.
     */
    private function setEnv(array $values)
    {
        $path = base_path('.env');
        $contents = (string) file_get_contents($path);
        // The backup holds every secret: readable by its owner only, outside the web root and git.
        $directory = storage_path('app/iaw');

        if (! is_dir($directory)) {
            mkdir($directory, 0700, true);
        }

        $backup = $directory.'/env.backup-'.date('YmdHis');
        copy($path, $backup);
        chmod($backup, 0600);

        foreach ($values as $name => $value) {
            $line = $name.'='.$value;
            $pattern = '/^'.preg_quote($name, '/').'=.*$/m';

            $contents = preg_match($pattern, $contents)
                ? preg_replace($pattern, $line, $contents)
                : rtrim($contents, "\n")."\n".$line."\n";
        }

        file_put_contents($path, $contents);
    }

    /**
     * The installer for legacy.itsallwidgets.com: a port-80 site first (so certbot can verify
     * the name), the certificate, then the HTTPS site that only answers requests carrying the
     * import key. It holds the key, so it's readable by its owner only.
     */
    private function writeInstaller($importKey)
    {
        $directory = storage_path('app/iaw');

        if (! is_dir($directory)) {
            mkdir($directory, 0700, true);
        }

        $root = public_path();
        $host = self::HOST;
        $key = str_replace(['\\', '"'], ['\\\\', '\\"'], $importKey);

        $script = <<<BASH
#!/usr/bin/env bash
# Written by php artisan iaw:prepare-export. Sets up {$host} for the new app's copies.
set -euo pipefail

SITE=/etc/nginx/sites-available/{$host}

echo "1/4 A port-80 site, so certbot can check the name"
cat > "\$SITE" <<'NGINX'
server {
    listen 80;
    listen [::]:80;
    server_name {$host};
    root {$root};

    location ^~ /.well-known/acme-challenge/ {
        allow all;
    }

    location / {
        return 404;
    }
}
NGINX
ln -sf "\$SITE" /etc/nginx/sites-enabled/{$host}
nginx -t
service nginx reload

echo "2/4 The certificate"
command -v certbot >/dev/null || { echo "certbot isn't installed: apt-get install certbot, then run this again."; exit 1; }
# Webroot: the port-80 site above serves the challenge from the app's public folder (the nginx
# plugin on this server is older than certbot). Renewals reload nginx.
certbot certonly --webroot -w {$root} -d {$host} --non-interactive --agree-tos --register-unsafely-without-email --keep-until-expiring --deploy-hook "service nginx reload"

echo "3/4 The HTTPS site, behind the import key"
cat > "\$SITE" <<'NGINX'
map \$http_x_iaw_import \$iaw_import_ok {
    default 0;
    "{$key}" 1;
}

server {
    listen 80;
    listen [::]:80;
    server_name {$host};
    root {$root};

    location ^~ /.well-known/acme-challenge/ {
        allow all;
    }

    location / {
        return 404;
    }
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name {$host};
    root {$root};
    index index.php;

    ssl_certificate /etc/letsencrypt/live/{$host}/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/{$host}/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;

    # The copies are JSON pages of a few MB.
    gzip on;
    gzip_types application/json;

    if (\$iaw_import_ok = 0) {
        return 404;
    }

    # Media straight from disk.
    location ~ ^/(screenshots|gifs|avatars|thumbnails|streams)/ {
        try_files \$uri =404;
    }

    # The export route (and its MP3s) go through the old app; it keeps answering in maintenance.
    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
    }
}
NGINX
nginx -t
service nginx reload

echo "4/4 PHP-FPM, so the new .env and code are live"
service php7.3-fpm reload

echo "Done: {$host} answers the new app (and nothing else)."
BASH;

        $path = $directory.'/install-legacy-host.sh';
        file_put_contents($path, $script);
        chmod($path, 0700);

        return $path;
    }
}
