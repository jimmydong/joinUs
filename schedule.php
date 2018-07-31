<?php 
include("include.inc.php");
show_header();
$me = check_login();
$date = date('Y-m-d');
$rows = $db->fetchAll("select * from appointment where date >= '{$date}' order by date");
$list = [];
foreach($rows as $row){
	if($row['user_id'] == $me['id'] || in_array($me['id'], json_decode($row['json_user'], true)?:[])){
		$star = '';
		for($i=0;$i<intval($row['rank']/2);$i++){
			$star .= "★";
		}
		if($row['rank']%2) $star .= "☆";
		
		$list[] = [
				'game'	=> Util::getById('game', $row['game_id']),
				'yard'	=> Util::getById('yard', $row['yard_id']),
				'title'	=> ($row['user_id'] == $me['id']) ? '我发起的' : '我报名了',
				'star'	=> $star,
				'date'	=> $row['date']
		];
	}
}
?>
  	<div id="app" class="container">
  		<my-menu active_name="3"></my-menu>
	    <br>
	    <h3>我的日程</h3>
	    <Row class="schedule header">
	    	<i-Col span="8">时间</i-Col>
	    	<i-Col span="16">明天</i-Col>
	    </Row>
	    <Row v-for="item,index in list" :key="index" class="schedule">
	    	<i-Col span="8">{{item.date}}</i-Col>
	    	<i-Col span="16">{{item.title}} : {{item.game.name}} {{item.star}} - {{item.yard.name}}</i-Col>
	    </Row>
  	</div>
  	
  </body>
  <script src="component/myMenu.js"></script>
  <script>
  	var data = {
  		  	list: <?php echo json_encode($list);?>
  	}

  	var app = new Vue({
  		el: '#app',
  		data: data,
  		methods:{
  		}
  	})
  </script>
  
</html>