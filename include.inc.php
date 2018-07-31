<?php
if($_REQUEST['debug']){
	define('PRODUCT', false);
	error_reporting(E_ALL);
}else{
	define('PRODUCT', true);
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
}
class DB extends SQLite3
{
	function __construct()
	{
		$this->open('sqlite3db.db');
	}
	function showError($sql = ''){
		echo "<pre>";
		if($sql) echo "sql: " . $sql . "\n";
		echo $this->lastErrorMsg();
		echo "</pre>";
	}
	function fetchOne($sql){
		$query = $this->doQuery($sql);
		if(! $query) return $this->showError();
		$re = $query->fetchArray(SQLITE3_ASSOC);
		return $re;
	}
	function fetchAll($sql){
		$result = $this->doQuery($sql);
		if(! $result) return $this->showError();
		$re = [];
		while( $t = $result->fetchArray(SQLITE3_ASSOC)) $re[] = $t;
		return $re;
	}
	function slash($string){
		return addslashes($string);
	}
	function insert($table, $data){
		$sql = "INSERT INTO `{$table}` ";
		$key_list = [];
		$val_list = [];
		foreach($data as $key=>$val){
			$key_list[] = $key;
			$val_list[] = "'" . $this->slash($val) . "'";
		}
		$sql .= "(" . implode(', ', $key_list) . ") VALUES (" . implode(', ', $val_list) . ")";
		$ret = $this->doExec($sql);
		if(!$ret) return $this->showError();
		else return true;
	}
	function update($table, $data, $where){
		$sql = "UPDATE `{$table}` SET ";
		$set = [];
		foreach($data as $k=>$v){
			$set[] = "{$k} = '".$this->slash($v)."'";
		}
		$sql .= implode(',', $set) . " WHERE " . $where;
		$ret = $this->doExec($sql);
		if(!$ret) return $this->showError();
		else return true;
	}
	function doExec($sql){
		if(!PRODUCT)var_dump("exec: " . $sql);
		return $this->exec($sql);
	}
	function doQuery($sql){
		if(!PRODUCT)var_dump("query: " . $sql);
		return $this->query($sql);
	}
}
$db = new DB();

/**
 * 头部
 */
function show_header(){
	print <<< end_of_print
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0" />
	<title>join-me</title>
	<link rel="stylesheet" href="//unpkg.com/iview/dist/styles/iview.css">
	<link rel="stylesheet" href="style/full.css">
	<script src="https://cdn.bootcss.com/vue/2.5.16/vue.js"></script>
	<script src="//unpkg.com/iview/dist/iview.min.js"></script>
	<script src="https://cdn.staticfile.org/jquery/3.2.1/jquery.min.js" charset="utf-8"></script>
	<script src="component/tools.js"></script>
	</head>
	<body>
end_of_print;
	
}
/**
 * 页面转跳
 */
function redirect($url,$msg='',$time=0, $filename='', $line=0){
		header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
		header('Location:' . $url);
		exit;
}
function check_login(){
	global $db;
	if(! $name = $_COOKIE['name']) return redirect('login.php');
	if(! $user = $db->fetchOne("select * from user where name='{$name}'")) return redirect('login.php');
	return $user;
}
function json_ok($msg = 'OK', $data = null){
	echo json_encode(array('success'=>true, 'msg'=>$msg, 'data'=>$data), JSON_UNESCAPED_UNICODE);
	exit;
}
function json_fail($msg = 'Fail', $data = null){
	echo json_encode(array('success'=>false, 'msg'=>$msg, 'data'=>$data), JSON_UNESCAPED_UNICODE);
	exit;
}

/**
 * 调试函数，用以取代var_dump。
 * 不同于var_dump：多个参数间使用 , 分隔，而不是空格
 */
function var_dump2(){
	$varArray = func_get_args();
	header("Content-Type: text/html;charset=utf8");
	foreach($varArray as $var) var_dump($var);
	$t = debug_backtrace(1);
	$caller = $t[0]['file'].':'.$t[0]['line'];
	echo " -- from $caller --";
	exit;
}
/**
 * 辅助操作类 
 *
 */
class Util{
	static public function init(){
		global $db;
		foreach(['game','yard','user','appointment'] as $table) $db->doExec("drop table {$table}");
		
		//创建表格
		$sql = 'create table game (id INTEGER PRIMARY KEY, name)';
		$db->doExec($sql);
		$sql = 'create table yard (id INTEGER PRIMARY KEY, name, address, lng, lat)';
		$db->doExec($sql);
		$sql = 'create table user (id INTEGER PRIMARY KEY, name, passwd, nickname, gender, json_rank)';
		$db->doExec($sql);
		$sql = 'create table appointment (id INTEGER PRIMARY KEY, game_id, yard_id, user_id, rank, date, max_number, json_user)';
		$db->doExec($sql);
		//初始化数据
		$game = ['足球','篮球','棒球','冰球','排球'];
		foreach($game as $val){
			$db->insert('game', ['name'=>$val]);
		}
		$yard = [
				['name' => '麦迪逊公园', 'address'=> '麦迪逊大道125号', 'lng'=>'-73.945192', 'lat'=>'40.804145'],
				['name' => '莫宁塞得公园', 'address'=> 'wait input ...', 'lng'=>'-73.958002', 'lat'=>'40.806906'],
				['name' => '西街花园', 'address'=> 'wait input ...', 'lng'=>'-73.962616', 'lat'=>'40.802829'],
				['name' => '弗莱德游乐场', 'address'=> 'wait input ...', 'lng'=>'-73.967208', 'lat'=>'40.797696'],
				['name' => '滑板公园', 'address'=> 'wait input ...', 'lng'=>'-73.970383', 'lat'=>'40.804811'],
		];
		foreach($yard as $val){
			$db->insert('yard', $val);
		}
		$user = ['jason','john','sam','kate','lily','young','lee','mary'];
		foreach($user as $val){
			$rank_info = [];
			$rows = $db->fetchAll('select * from game');
			foreach($rows as $row){
				$rank_info[$row['id']] = rand(1,10);
			}
			$db->insert('user', [
					'name'		=> $val,
					'passwd'	=> '123456',
					'nickname'	=> $val,
					'gender'	=> rand(0,2),
					'json_rank'	=> json_encode($rank_info)
			]);
		}
		//预约案例
		$game_list = $db->fetchAll("select * from game");
		$yard_list = $db->fetchAll("select * from yard");
		$user_list = $db->fetchAll("select * from user");
		foreach($user_list as $user){
			$game = $game_list[array_rand($game_list)];
			$yard = $yard_list[array_rand($yard_list)];
			$user_rank = json_decode(stripcslashes($user['json_rank']), true);
			$db->insert('appointment', [
					'game_id'	=> $game['id'],
					'yard_id'	=> $yard['id'],
					'user_id'	=> $user['id'],
					'rank'		=> $user_rank[$game['id']],
					'date'		=> date('Y-m-d', time() + 3600*24*rand(1,3)),
					'max_number'=> rand(3,10),
					'json_user'	=> json_encode([])
			]);
		}
	}
	static public function check($table){
		global $db;
		$re = $db->fetchAll("select * from `{$table}`");
		var_dump($re);
	}
	static public function getById($table, $id){
		global $db;
		$re = $db->fetchOne("select * from $table where id = '$id'");
		return $re;
	}
	static public function getGameList(){
		global $db;
		$rows = $db->fetchAll("select * from game");
		$re = [];
		foreach($rows as $row){
			$re[$row['id']] = $row;
		}
		return $re;
	}
	static public function getYardList(){
		global $db;
		$rows = $db->fetchAll("select * from yard");
		$re = [];
		foreach($rows as $row){
			$re[$row['id']] = $row;
		}
		return $re;
	}
}