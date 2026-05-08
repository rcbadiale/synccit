#!/bin/sh
set -e

php /init-db.php
exec apache2-foreground
