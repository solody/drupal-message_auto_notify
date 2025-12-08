#!/bin/bash
cd /app
rm -f web/modules/contrib/message_auto_notify/tests/fixtures/update/8000-db-dump.php.gz
php web/core/scripts/db-tools.php dump-database-d8-mysql  | gzip > web/modules/contrib/message_auto_notify/tests/fixtures/update/8000-db-dump.php.gz
