

<?php
ini_set('display_errors', 1);
require_once('TwitterAPIExchange.php');

/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "1594039256-OD0N2Qc6us6nrbQ4HW4ROMBbCZSz96Bp7iek6eD",
    'oauth_access_token_secret' => "sKRuRFMwwqajbur8PfutTs7wbTPYzmaOD39VvZxRwVIE3",
    'consumer_key' => "tiAdFxoa1bljuvVf4uhW20ODp",
    'consumer_secret' => "tIPPoqqCh5Y0afKEGHbvuBfWDML8Ij3Q75IsdJuKtioqayddfa"
);

/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
$url = 'https://api.twitter.com/1.1/blocks/create.json';
$requestMethod = 'POST';

/** POST fields required by the URL above. See relevant docs as above **/
$postfields = array(
    'screen_name' => 'usernameToBlock', 
    'skip_status' => '1'
);

/** Perform a POST request and echo the response
$twitter = new TwitterAPIExchange($settings);
echo $twitter->buildOauth($url, $requestMethod)
             ->setPostfields($postfields)
             ->performRequest(); **/

/** Perform a GET request and echo the response **/
/** Note: Set the GET field BEFORE calling buildOauth(); **/
//$url = 'https://api.twitter.com/1.1/followers/ids.json';
$query = $_POST["search"];


$url ='https://api.twitter.com/1.1/search/tweets.json';
$getfield = '?q='.$query;



$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
echo $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();
