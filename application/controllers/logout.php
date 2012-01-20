<?php
class Logout extends CI_Controller {
    public function index() {
        
		session_start();
		session_destroy();
		
		header("Location: /home");
    }
}


/*
11:37 (tarop) callback で user_id とか受け取ったら
11:37 (tarop) そこでセッションに user_id 入れて
11:38 (tarop) それで管理ページにリダイレクトしたらいいと思うよ
11:38 (tarop) 管理ページでは、例えば user_id がセッションに入っていたらログイン状態とする
11:38 (tarop) みたいなことにしておけば
11:38 (tarop) user_id 持ってない人はログインしてないので
11:38 (tarop) ログインページにリダイレクトする
11:39 (tarop) そんな感じかな
11:39 (tarop) ちなみにログアウトするときはセッションを破棄する
11:39 (tarop) これで概ねいいんじゃないかと思う
*/