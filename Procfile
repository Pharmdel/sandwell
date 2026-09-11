# Railpack caches config during the build, when Railway's variables may not yet
# exist — a key added afterwards never reaches the cached config, and the app
# fatals on every request. Re-caching at container start binds the cache to the
# runtime environment instead. Directories are created because the SQLite file
# and framework caches live on the container filesystem, not in the image.
web: mkdir -p database storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache && touch database/database.sqlite && chmod -R a+rw database storage bootstrap/cache && php artisan config:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
