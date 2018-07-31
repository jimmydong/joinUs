<?php
include('include.inc.php');
$me = check_login();

if($_REQUEST['do'] == 'add'){
	if(! $game = $_REQUEST['game']){
		json_fail('活动类型错误');
	}
	if(! $yard = $_REQUEST['yard']){
		json_fail('活动场地错误');
	}
	if(! $max_number = $_REQUEST['max_number']){
		$max_number = 20;
	}
	$my_rank = json_decode(stripslashes($me['json_rank']), true);
	if(! $rank = $my_rank[$game]){
		json_fail('级别计算错误');
	}
	$db->insert('appointment', [
			'user_id'		=> $me['id'],
			'game_id'		=> $game,
			'yard_id'		=> $yard,
			'max_number'	=> $max_number,
			'rank'			=> $rank
	]);
	
	json_ok();
}

if($_REQUEST['do'] == 'join'){
	if(! $id = $_REQUEST['id']){
		json_fail('ID错误');
	}
	if(! $appointment = Util::getById('appointment', $id)){
		json_fail('活动异常');
	}
	$user_list = json_decode(stripcslashes($appointment['json_user']), true);
	if(in_array($me['id'], $user_list)){
		json_fail('已经加入过');
	}
	
	$user_list[] = $me['id'];
	
	$db->update('appointment', ['json_user'=>json_encode($user_list)], "id='{$id}'");
	
	json_ok();
}