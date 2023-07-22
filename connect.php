<?php
require 'vendor/autoload.php';
require 'config.php';
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
$client = new Client(['verify'=> 'cacert.pem']);
try{
$response = $client->request('GET', 'https://accounts.google.com/.well-known/openid-configuration');
 $discovery = json_decode($response->getBody());
$token_endpoint = $discovery->token_endpoint;
$userinfo_endpoint =  $discovery->userinfo_endpoint;


$response = $client->request('POST',$token_endpoint,
['form_params' => [
    'code' => $_GET['code'],
    'client_id' => ID,
    'client_secret'=> SECRET,
    'redirect_uri'=> 'http://localhost:8000/connect.php',
    'grant_type'=> 'authorization_code',
] ]);
$access_token = json_decode($response->getBody())->access_token;
$response = $client->request('GET',$userinfo_endpoint ,
[
    'headers'=>
    ['Authorization' => 'Bearer' . $access_token]
] );
$email = json_decode($response->getBody())->email;
if(filter_var($email,FILTER_VALIDATE_EMAIL) === TRUE){
    session_start();
    $_SESSION['email']=== $email;
}
}catch(ClientException $e){
    dd($e);
}
?>