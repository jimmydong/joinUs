<?php
include('include.inc.php');
$me = check_login();

if($_REQUEST['do'] == 'add'){
	if(! $game = $_REQUEST['game']){
		json_fail('Type wrong');
	}
	if(! $yard = $_REQUEST['yard']){
		json_fail('Yard wrong');
	}
	if(! $max_number = $_REQUEST['max_number']){
		$max_number = 20;
	}
	$my_rank = json_decode(stripslashes($me['json_rank']), true);
	if(! $rank = $my_rank[$game]){
		json_fail('Rank wrong');
	}
	$db->insert('appointment', [
			'user_id'		=> $me['id'],
			'game_id'		=> $game,
			'yard_id'		=> $yard,
			'max_number'	=> $max_number,
			'date'			=> $_REQUEST['date'],
			'rank'			=> $rank
	]);
	
	json_ok();
}

if($_REQUEST['do'] == 'join'){
	if(! $id = $_REQUEST['id']){
		json_fail('ID wrong');
	}
	if(! $appointment = Util::getById('appointment', $id)){
		json_fail('Appointment wrong');
	}
	$user_list = json_decode(stripcslashes($appointment['json_user']), true);
	if(in_array($me['id'], $user_list)){
		json_fail('Had joined');
	}
	
	$user_list[] = $me['id'];
	
	$db->update('appointment', ['json_user'=>json_encode($user_list)], "id='{$id}'");
	
	json_ok();
}