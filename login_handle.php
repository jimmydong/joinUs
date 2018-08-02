<?php
include('include.inc.php');

if(! $name = $_REQUEST['mobile']) return json_fail('No name');
if(! $passwd = $_REQUEST['passwd']) return json_fail('No password');

if($_REQUEST['do'] == 'login'){
	if(! $user = $db->fetchOne("select * from user where name='{$name}' and passwd='{$passwd}'")){
		return json_fail('Wrong name or password');
	}
	setcookie('name',$name);
	return json_ok();
}else{
	if($user = $db->fetchOne("select * from user where name='{$name}'")){
		return json_fail('Same name already exist');
	}
	if(! $db->insert('user', [
			'name'		=> $name,
			'passwd'	=> $passwd,
			'nickname'	=> $name
	])){
		return json_fail('System wrong');
	}
	setcookie('name',$name);
	return json_ok();
}