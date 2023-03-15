<?php
$url = "http://mage.magento.com/rest";
$token_url = $url."/V1/integration/admin/token";
echo $token_url;
$username = "rohit";
$password = "Roh@100mageget";

//Authentication rest API magento2, For get access token
$ch = curl_init();
var_dump($ch);
$data = array("username" => $username, "password" => $password);
$data_string = json_encode($data);

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $token_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$token = curl_exec($ch);
$accessToken = json_decode($token);
echo $accessToken;
