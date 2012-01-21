<?php
//$text1 = $_POST['text1'];

// twitteroauth.phpを読み込む。パスはあなたが置いた適切な場所に変更してください
require_once("twitteroauth.php");

// Consumer keyの値
$consumer_key = "5EgWHBYmShjw6MbkwwgRvg";
// Consumer secretの値
$consumer_secret = "XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI";
// Access Tokenの値
$access_token = "440891995-rngEpAUOLVlRzZTqk6L41I96LmJ3x8T3uvjcmMuJ";
// Access Token Secretの値
$access_token_secret = "ReD9s4a23a79NKqBw64hIvlLxBNfBJ8hfcA3UihyhAM";

// OAuthオブジェクト生成
$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

// TwitterへPOSTする。パラメーターは配列に格納する
// in_reply_to_status_idを指定するのならば array("status"=>"@hogehoge reply","in_reply_to_status_id"=>"0000000000"); とする。

//$req = $to->OAuthRequest("http://api.twitter.com/1/statuses/update.xml","POST",array("status"=>$text1));

//$req = $to->OAuthRequest("http://api.twitter.com/1/statuses/update.xml","POST",$text1);

//$text1 = $_POST['text1'];
//$req = $to->OAuthRequest("http://api.twitter.com/1/statuses/update.xml","POST",array("status"=>$text1));

$req = $to->OAuthRequest("http://api.twitter.com/1/statuses/update.xml","POST",array("status"=>"おなかいっぱいでねむい"));

// TwitterへPOSTするときのパラメーターなど詳しい情報はTwitterのAPI仕様書を参照してください

header("Content-Type: application/xml");
echo $req;
?>