#!/bin/bash

# 1. Make folder config to user www-data
mkdir -p /var/www/.msmtp

# 2. Create config msmtp file from environment variables
cat > /var/www/.msmtp/config <<EOF
defaults
auth           on
tls            on
tls_trust_file /etc/ssl/certs/ca-certificates.crt
logfile        /var/log/msmtp.log

account        default
host           smtp.gmail.com
port           587
from           ${MAIL_USER}
user           ${MAIL_USER}
password       ${MAIL_PASS}
EOF

# 3. Change file owner right to www-data
chown -R www-data:www-data /var/www/.msmtp

# 4. Only user www-data can read this file
chmod 600 /var/www/.msmtp/config

# 5. Make log file and give owner to www-data
touch /var/log/msmtp.log
chown www-data:www-data /var/log/msmtp.log

# 6. Restart PHP-FPM
exec php-fpm