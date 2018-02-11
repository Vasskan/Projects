<?php

$url = 'http://smpp.smsclub.mobi/hfw_smpp_addon/httpsendsms.php?';

$username = 'username'; // username in service smsclub.mobi
$password = 'password'; // account password in service smsclub.mobi
$from = 'club_bulk'; // sms sender alias
$sender = $_REQUEST['sender'];
$to = $_REQUEST['phoneNumber']; // receiver phone number
$text = $_REQUEST['smsMessage']; // message

$url_result = $url.'username='.$username.'&password='.$password.'&from='.urlencode($from).'&to='.$to.'&text='.base64_encode(iconv("UTF-8", "windows-1251", $text));

if( $curl = curl_init() )
{
    curl_setopt($curl, CURLOPT_URL, $url_result);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    $out = curl_exec($curl);
    echo $out;
    curl_close($curl);
}

//Connection parameters - data source name (variable $dsn)

$dsn = 'mysql:host=localhost; dbname=sms_db';
$db_user = 'root';
$db_password = '';
$dbh = new PDO($dsn, $db_user, $db_password);

try {
    $dbh = new PDO($dsn, $db_user, $db_password);
    // set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO sms (sender, phone_number, sms_text)
    VALUES ('$sender', '$to', '$text')";
    // use exec() because no results are returned
    $dbh->exec($sql);
    echo "New record created successfully";
} catch (PDOException $e) {
    echo $e->getMessage();
}

header("Location: /sms/index.html");