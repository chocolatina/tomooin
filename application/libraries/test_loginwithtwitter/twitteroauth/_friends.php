<?php
// コマンドラインで実行してください。
 
// twitteroauthを利用
require_once('twitteroauth.php');
 
// Consumer keyの値
$consumer_key = "5EgWHBYmShjw6MbkwwgRvg";
// Consumer secretの値
$consumer_secret = "XV32VsHrSa3BKbdmOPTwyu18IbqAunQruBTCTmnW9xI";
// Access Tokenの値
$access_token = "440891995-rngEpAUOLVlRzZTqk6L41I96LmJ3x8T3uvjcmMuJ";
// Access Token Secretの値
$access_token_secret = "ReD9s4a23a79NKqBw64hIvlLxBNfBJ8hfcA3UihyhAM";
 
// OAuthオブジェクトの生成
$connect = new TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_token_secret);
$connect->format = "xml";
 
// フォローしてるユーザーのID一覧を取得
$api_url = "http://api.twitter.com/1/friends/ids.xml";
$method = "GET";
$option = array("screen_name" => "chocolatina");
$req = $connect->OAuthRequest($api_url,$method,$option);

$xml = simplexml_load_string($req);
 
echo $req;

foreach ($xml->ids->id as $id){
	echo $id."<br />\n";
	
};


// OAuthオブジェクト生成
$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);

// home_timelineの取得。TwitterからXML形式が返ってくる
$req = $to->OAuthRequest("http://twitter.com/account/verify_credentials.xml","GET");

// XML文字列をオブジェクトに代入する
$xml = simplexml_load_string($req);

echo $req;

// foreachで呟きの分だけループする
/*foreach($xml->status as $status){
      $status_id = $status->id; // 呟きのステータスID
      $text = $status->text; // 呟き
      $user_id = $status->user->id; // ユーザーナンバー
      $screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
      $name = $status->user->name; // ユーザーの名前（HNなど）
      echo "<p><b>".$screen_name." / ".$name."</b> <a href=\"http://twitter.com/".$screen_name."/status/".$status_id."\">この呟きのパーマリンク</a><br />\n".$text."</p>\n";
}*/


//http://twitter.com/account/verify_credentials.xml



// OAuthオブジェクト生成
/*$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);*/

//$apiurl="http://api.twitter.com/1/users/show.xml?user_id="."106932662";

// home_timelineの取得。TwitterからXML形式が返ってくる
/*$req = $to->OAuthRequest("http://api.twitter.com/1/users/show.xml?user_id=106932662","GET");*/

// XML文字列をオブジェクトに代入する
/*$xml = simplexml_load_string($req);*/

// foreachで呟きの分だけループする
/*foreach($xml->status as $status){
      $status_id = $status->id; // 呟きのステータスID
      $text = $status->text; // 呟き
      $user_id = $status->user->id; // ユーザーナンバー
      $screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
      $name = $status->user->name; // ユーザーの名前（HNなど）
      echo "<p><b>".$screen_name." / ".$name."</b> <a href=\"http://twitter.com/".$screen_name."/status/".$status_id."\">この呟きのパーマリンク</a><br />\n".$text."</p>\n";
}*/


//タイムライン表示
// OAuthオブジェクト生成
/*$to = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);*/

// home_timelineの取得。TwitterからXML形式が返ってくる
/*$req = $to->OAuthRequest("http://api.twitter.com/1/statuses/home_timeline.xml","GET",array("count"=>"50"));*/

// XML文字列をオブジェクトに代入する
/*$xml = simplexml_load_string($req);*/

// foreachで呟きの分だけループする
/*foreach($xml->status as $status){
      $status_id = $status->id; // 呟きのステータスID
      $text = $status->text; // 呟き
      $user_id = $status->user->id; // ユーザーナンバー
      $screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
      $name = $status->user->name; // ユーザーの名前（HNなど）
      echo "<p><b>".$screen_name." / ".$name."</b> <a href=\"http://twitter.com/".$screen_name."/status/".$status_id."\">この呟きのパーマリンク</a><br />\n".$text."</p>\n";
}*/


	
	/*$api_url2 = "https://api.twitter.com/1/users/show.xml?user_id=440891995";
	$method2 = "GET";
	$req2 = $connect->OAuthRequest($api_url2,$method2);
	
	echo $req2;*/






// フォローしてるユーザーの
/*$api_url = "http://api.twitter.com/1/statuses/public_timeline.xml";
$method = "GET";
$option = array("screen_name" => "chocolatina");
$req = $connect->OAuthRequest($api_url,$method,$option);

$xml = simplexml_load_string($req);
 
echo $req;*/


//print_r(json_decode(file_get_contents("http://api.twitter.com/1/statuses/public_timeline.json")));


?>