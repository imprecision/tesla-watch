<?php

/**
 * Constants
 */

include "config.php";

/**
 * Init
 */

include "vendor/autoload.php";

/**
 * Cache
 */

define('WRWDSL_STASH_CONTACTS_REF', 'imprecision/tesla-watch');
$stash_driver = new \Stash\Driver\FileSystem();
$stash_pool = new \Stash\Pool($stash_driver);

// 1 in n calls will trigger a purge (clean-up)
if (rand(0, 20) === 5) {
    $stash_pool->purge();
}

$stash_item = $stash_pool->getItem(WRWDSL_STASH_CONTACTS_REF);
if ($stash_item->isHit() === false) {
    $info = [];
} else {
    $info = $stash_item->get();
}

if (!is_array($info)) {
    $info = [];
}

/**
 * Network
 */

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, TWATCH_URL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
$json = curl_exec($ch);
curl_close($ch);

/**
 * Process
 */

$data = json_decode($json, true);

$send_email = false;
$output = "";

$output = "<style>li, td {font-size:12px;} tr:nth-child(even) {background: #CCC} tr:nth-child(odd) {background: #FFF} td {padding: 15px;}</style>";
$output .= "<ul>";
$output .= sprintf("<li>%s</li>\n", TWATCH_URL);
$output .= sprintf("<li>%s</li>\n", date("r"));
$output .= sprintf("<li>%s vehicles</li>\n", $data["total_matches_found"]);
$output .= "</ul>";

if (isset($data["total_matches_found"]) && is_numeric($data["total_matches_found"]) && ($data["total_matches_found"] > 0)) {
    $output .= "<table>";
    if (isset($data["results"]) && is_array($data["results"])) {
        foreach ($data["results"] as $car) {
            if (isset($car["VIN"])) {
                if (isset($info[$car["VIN"]])) {
                    $firstseen = $info[$car["VIN"]];
                } else {
                    $info[$car["VIN"]] = date("r");
                    $firstseen = "NEW!";
                    $send_email = true;
                }
                $output .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s %s</td><td>%s</tr>\n", $car["VIN"], $car["Price"], $car["TrimName"], $car["Odometer"], $car["OdometerType"], $firstseen);
            }
        }
    }
    $output .= "</table>";
} else {
    $output .= "<p>No vehicles found.</p>";
}

print $output;

/**
 * Cache
 */

$stash_item->lock();
$stash_item->set($info);
$stash_pool->save($stash_item);

/**
 * Send email
 */

if ($send_email) {
    $mail = new \PHPMailer\PHPMailer\PHPMailer();
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    try {
        $mail->isSMTP();
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = TWATCH_SEC;
        $mail->Host       = TWATCH_HOST;
        $mail->Port       = TWATCH_PORT;
        $mail->Username   = TWATCH_USER;
        $mail->Password   = TWATCH_PASS;
        $mail->From       = TWATCH_FROM;
        $mail->FromName   = "tesla-watch";
        $mail->Subject    = "New t-watch!";
        $mail->msgHTML($output);
        $mail->addAddress(TWATCH_TO);
        $mail->isHTML(true);
        $mail->send();
    } catch (phpmailerException $e) {
        // $this->l($e->errorMessage());
    } catch (Exception $e) {
        // $this->l($e->getMessage());
    }
}
