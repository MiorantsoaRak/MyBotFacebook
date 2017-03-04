<?php
$access_token = "EAAaH3eIGEwYBAKWCrCRiz2ThaapIPzWPAI5d4R3XiEkm4fIEHyLaBKTfB1fB5YAFO5TIvQXe6DnH5I1Dendbk2YBXlVOnIvtNbZBG7XLWU0KkCFel5SvLMtmlcerSAsaaZAuNKVDL8dytCTAH3tqeASvT2VDvOZCRj39M6ZB1QZDZD";
$verify_token = "my_first_bot";
$hub_verify_token = null;
 
if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
 
 
if ($hub_verify_token === $verify_token) {
    echo $challenge;
}
//Decodage du message
$input = json_decode(file_get_contents('php://input'), true);

$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
//Traitement du message
/**
 * Some Basic rules to validate incoming messages
 */
$message_to_reply = "";
$result ="";
if(preg_match('[paris|libone|now]', strtolower($message))) {

    // Make request to Time API
    ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
    $result = "Vous avez demandé l'heure";
    // if($result != '') {
        $message_to_reply = $result;
    // }
} else {
    $message_to_reply = 'Huh! what do you mean?';
}
print $message_to_reply;

//Envoie du message à l'utilisateur
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;


//Initiate cURL.
$ch = curl_init($url);

//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';

//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message'])){
    var_dump(curl_exec($ch));
    // echo curl_error($ch);
}