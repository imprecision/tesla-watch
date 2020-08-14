# tesla-watch

A _really_ simple script to watch for new additions to Tesla's vehicle store front.

## Why?

Perhaps your region has limited stock which gets sold very quickly. This might help.

## How to configure...

Copy `config.example.php` to `config.php` and update with your email provider's SMTP details.

Update `TWATCH_URL` URL to correctly reflect the country's store you're interested in. 

## How to run...

From the command line:

    /usr/bin/php /your/location/tesla-watch/watch.php

## How to automate...

Put something like this in your cron to execute periodically and log output to a tmp file for debugging/history:

    3 */1 * * * /usr/bin/php /your/location/tesla-watch/watch.php >> /tmp/log-tesla-watch.log 2>&1
