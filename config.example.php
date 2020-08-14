<?php

// Tesla shop API URL - update to suit your region (e.g. this one is set up to watch Model 3s in Austria):

define('TWATCH_URL',  'https://www.tesla.com/inventory/api/v1/inventory-results?query=%7B%22query%22%3A%7B%22model%22%3A%22m3%22%2C%22condition%22%3A%22new%22%2C%22options%22%3A%7B%7D%2C%22arrangeby%22%3A%22Price%22%2C%22order%22%3A%22asc%22%2C%22market%22%3A%22AT%22%2C%22language%22%3A%22de%22%2C%22super_region%22%3A%22north%20america%22%2C%22lng%22%3A%22%22%2C%22lat%22%3A%22%22%2C%22zip%22%3A%22%22%2C%22range%22%3A0%7D%2C%22offset%22%3A0%2C%22count%22%3A50%2C%22outsideOffset%22%3A0%2C%22outsideSearch%22%3Afalse%7D');

// Email - update to match an SMTP account you have access to:

define('TWATCH_SEC',  'ssl');
define('TWATCH_HOST', 'your.smtp-provider.com');
define('TWATCH_PORT', 465);
define('TWATCH_USER', 'your-smtp-username');
define('TWATCH_PASS', 'your-smtp-password');
define('TWATCH_FROM', 'your@email-address.com');
define('TWATCH_TO',   'your@email-address.com');
