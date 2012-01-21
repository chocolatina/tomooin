<?php
$text1 = $_POST['text1'];

//var_dump($text1);
//exit;
//$text1 = "くちびるきれた";

// twitteroauth.phpを読み込む。パスはあなたが置いた適切な場所に変更してください
require_once("../twitteroauth/twitteroauth.php");

// Consumer keyの値
$consumer_key = "4GOzy7yh81aWCfVyORLyQ";
// Consumer secretの値
$consumer_secret = "0B8kP46nQdCXoLitNV6v8s8ZQs1cl1kBcVO6rpoGfw";
// Access Tokenの値
$access_token = "14212710-l8c99ydpKHrDOWSEntxIdtclICOQfZaC3DqMQbYJ4";
// Access Token Secretの値
$access_token_secret = "mTxz4TjXXjwuDcrnmhnPuLJowJmkxQTGUGcBGVzz1U";

// OAuthオブジェクト生成
$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

$req = $to->OAuthRequest("http://api.twitter.com/1/statuses/update.xml","POST",array("status"=>$text1));

//$req = $to->OAuthRequest("http://api.twitter.com/1/statuses/update.xml","POST",array("status"=>"ガムおいしいですか"));

header("Content-Type: application/xml");
echo $req;
?>