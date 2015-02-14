

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
$getfield = '?q='.urlencode($query);



$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
echo $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();
$response = $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();

//connect to server, connect to database
    $connect = new mysqli('localhost', 'root', 'root','twitter');

        $json = json_decode($response);
        //Writes the beginning of the one replace statement
        //we use replace instead of insert because if we insert a post that already exists we will get
        //a duplicate key error. Replace will delete the old row and replace it with a new row if we attempt to
        //insert row with a key that was already used
        $insert = "REPLACE INTO twitter.results (search, id_str,created_at,user_id,location,textf) VALUES ";

        //loops through each post in the JSON file
        foreach($json->statuses as $postInformation){

            //concatenates all of the information needed for a post in between parenthesis and separated by comments
            //It writes the part of the insert statement that we need for each post
            //because title can apostrophes in it, we put it in a variable first so that we can put it in a function
            //that escapes the apostrophes
            $values  = "('".$query."' , '";
            $values .= $postInformation->id_str."' , '";
            $values .= $postInformation->created_at."' , '";
            $values .= $postInformation->user->id_str."' , '";
            $values .= $postInformation->user->location."' , '";
            $values .= mysqli_real_escape_string($connect,$postInformation->text)."'),";
            //appends each post to the replace statement so that we can send all of the posts to the database
            //at once instead of one at a time
            $insert = $insert.$values;
        }//end foreach loop

        //We concatenated a comma at the end each of the posts $value statement to compose our one query.
        //However for the one, we do not want that comma, so we get rid of it and add a semicolon to the end
        //to complete our insert query.

        $insert2 = substr($insert,0, -1).";";
        //echo $insert2;

        //Run the query that was written or show an error if it can't run
        //$insertComplete = mysql_query($insert2,$connect)or die('Tried to run the insert, here was the error I received: '.mysql_error());
        if (mysqli_query($connect, $insert2)) {
            //echo "New record created successfully";
        } else {
            echo "Error: " . $insert2 . "<br>" . mysqli_error($connect);
        }
        
        
//close database connection
   mysqli_close($connect);
   