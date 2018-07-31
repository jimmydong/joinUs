<?php
include('include.inc.php');

if(! $name = $_REQUEST['mobile']) return json_fail('没有用户名');
if(! $passwd = $_REQUEST['passwd']) return json_fail('没有密码');

if($_REQUEST['do'] == 'login'){
	if(! $user = $db->fetchOne("select * from user where name='{$name}' and passwd='{$passwd}'")){
		return json_fail('用户名或密码错');
	}
	setcookie('name',$name);
	return json_ok();
}else{
	if($user = $db->fetchOne("select * from user where name='{$name}'")){
		return json_fail('同名用户已存在');
	}
	if(! $db->insert('user', [
			'name'		=> $name,
			'passwd'	=> $passwd,
			'nickname'	=> $name
	])){
		return json_fail('系统错误');
	}
	setcookie('name',$name);
	return json_ok();
}