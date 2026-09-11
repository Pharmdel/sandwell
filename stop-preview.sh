#!/usr/bin/env bash
#
# Takes the shared preview offline and puts the project back into development mode.
# Safe to run twice: every step is skipped if it has already happened.
#
#   ./stop-preview.sh
#
set -u
cd "$(dirname "$0")"

say() { printf '  %s\n' "$1"; }

echo "Stopping the preview…"

# 1. The public link. Killing the tunnel is what actually takes the site offline;
#    the URL is not reserved, so it cannot be reclaimed afterwards.
pkill -f 'ssh .*localhost\.run'  2>/dev/null && say 'tunnel closed — the public link is now dead' \
                                 || say 'tunnel was not running'
pkill -f 'cloudflared tunnel'    2>/dev/null && say 'cloudflared stopped' || true

# 2. The local web server.
pkill -f 'artisan serve'         2>/dev/null && say 'web server stopped' \
                                 || say 'web server was not running'

# 3. Let the Mac sleep again.
pkill -f 'caffeinate -is'        2>/dev/null && say 'sleep re-enabled' \
                                 || say 'caffeinate was not running'

# 4. Back to development settings.
if [ -f .env.backup.local ]; then
    cp .env.backup.local .env
    say 'restored .env (APP_ENV=local, APP_DEBUG=true)'
else
    say 'no .env.backup.local found — leaving .env alone'
fi

php artisan config:clear >/dev/null 2>&1 && say 'config cache cleared'

echo
echo "Done. Check nothing is left:"
echo "  ps aux | grep -E 'artisan serve|localhost.run|caffeinate'"
