<?php
include('include.inc.php');
$me = check_login();

if(! $nickname = $_REQUEST['nickname']){
	json_fail('昵称设定错误');
}
$game_list = Util::getGameList();
$my_rank = [];
foreach($_REQUEST['my_rank'] as $k=>$v){
	if($v > 10 || $v < 0) json_fail('等级设定错误');
	foreach($game_list as $game_id=>$game){
		if($game['name'] == $k)break;
	}
	$my_rank[$game_id] = $v;
}
$db->update('user',['nickname'=>$nickname, 'gender'=>$_REQUEST['gender'], 'json_rank'=>json_encode($my_rank)], "id='{$me['id']}'");

json_ok();