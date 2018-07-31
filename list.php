<?php 
include('include.inc.php');
$me = check_login();
show_header();

$my_rank = json_decode(stripslashes($me['json_rank']), true);
$where = [];
$my_game = [];
foreach($my_rank as $game_id=>$rank){
	if(! $rank)continue;
	$where[] = " (game_id='{$game_id}' and rank='{$rank}') ";
	$my_game[$game_id] = Util::getById('game', $game_id)['name'] . "(级别：{$rank})";
}
if($where){
	$list = $db->fetchAll("select * from appointment where  (" . implode(' OR ', $where) . ") order by id desc");
	foreach($list as $key=>$val){
		$user = Util::getById('user', $val['user_id']);
		$list[$key]['game'] = Util::getById('game', $val['game_id'])['name'];
		$list[$key]['yard'] = Util::getById('yard', $val['yard_id'])['name'];
		$list[$key]['user'] = $user['name'];
		for($i=0;$i<intval($val['rank']/2);$i++){
			$list[$key]['star'] .= "★";
		}
		if($val['rank']%2) $list[$key]['star'] .= "☆";
		if($user['gender'] == 0) $list[$key]['icon'] = "star";
		elseif($user['gender' == 1]) $list[$key]['icon'] = "female";
		else $list[$key]['icon'] = "male";
		
		$list[$key]['number'] = count(json_decode(stripcslashes($val['json_user']), true)) + 1;
		
		if($val['user_id'] == $me['id']) $list[$key]['badge'] = "我发起的";
		else $list[$key]['badge'] = "立即加入";
	}
}else{
	$list = [];
}
//格式化
foreach($list as $key=>$val){
	$list[$key]['title'] = "{$val['yard']}，{$val['game']}，日期：{$val['date']}";
	$list[$key]['info'] = "已有{$val['number']}人 / 限{$val['max_number']}人，等级：{$val['star']}";
}
?>
  	<div id="app" class="container">
  		<my-menu active_name="2"></my-menu>
	    <br>
	    <h3>推荐的活动</h3>
	    <div>
			<Row :gutter=16 v-for="item,index in list" class="list" type="flex" align="middle" :key="index"> 
			<i-Col :xs="16" :sm="18" :md="20" :lg="22"><div class="title" @click="click(index)"><Icon v-if="item.icon" :type="item.icon"></Icon> {{item.title}}</div>
				<div class="info" v-if="item.info" @click="click(index)">{{item.info}}</div></i-Col>
			<i-Col :xs="8" :sm="6" :md="4" :lg="2" class="right"><span class="badge" @click="click(index)" :style="item.color?('background:'+item.color):''">{{item.badge}}</span></i-Col>
			</Row>
		</div>
		<div v-if="no_list">
			暂无活动。快来发起一个吧~~
		</div>
  		<hr/>
  		<br/>
  		<i-Button type="primary" icon="plus" @click="show_add">我要发布</i-Button>
  		
  		<!-- 弹出层 -->
  		<Modal v-model="modal" width="400">
			<p slot="header" style="color:#f60;text-align:center">
				发布新活动
			</p>
			<Row :gutter="32">
				<Col span="18">
					<i-Form ref="addNew" :model="addNew" :rules="ruleInline" class="signin" style="margin:20px">
						<Form-Item prop="game">
						<i-Select type="text" v-model="addNew.game" placeholder="请选择活动类型">
							<i-Option v-for="item,index in my_game" :value="index" :key="index">{{item}}</Option>
						</i-Select>
						<p class="error-text" v-show="addNew.error.game">{{addNew.error.game}}</p>
						</Form-Item>
						<Form-Item prop="yard">
						<i-Select type="text" v-model="addNew.yard" placeholder="请选择活动场地">
							<i-Option v-for="item,index in yard_list" :value="index" :key="index">{{item.name}}</Option>
						</i-Select>
						<p class="error-text" v-show="addNew.error.yard">{{addNew.error.yard}}</p>
						</Form-Item>
						<Form-Item prop="date">
							<Date-Picker type="date" v-model="addNew.date" format="yyyy-mm-dd" placeholder="请选择日期"></Date-Picker>
							<p class="error-text" v-show="addNew.error.date">{{addNew.error.date}}</p>
						</Form-Item>
						<Form-Item prop="date">
							<i-Input type="text" v-model="addNew.max_number" placeholder="人数限制(不填写默认为20人)"></i-Input>
						</Form-Item>
						<Form-Item style="text-align:center">
						<p class="error-text" v-show="addNew.error.all">{{addNew.error.all}}</p>
						<i-Button type="primary" @click="add" style="width:60%;font-size:16px">发布</i-Button>
						</Form-Item>
					</i-Form>
				</Col>
			</Row>
			<div slot="footer" style="text-align:center">
				<p></p>
			</div>
		</Modal>
  	</div>
  	
  </body>
  <script src="component/myMenu.js"></script>
	
  <script>
  	var data = {
		list: <?php echo json_encode($list);?>,
		modal: false,
		my_game: <?php echo json_encode($my_game);?>,
		yard_list: <?php echo json_encode(Util::getYardList());?>,
		addNew: {
			game: '',
			yard: '',
			date: '',
			max_number: '',
			error: {
			}
		},
		ruleInline: {
			game: [],
			yard: []
		}
  	}

  	var app = new Vue({
  		el: '#app',
  		data: data,
  		methods:{
  			click: function(index) {
  	  			self = this;
  	  			if(this.list[index].user_id == '<?php echo $me['id'];?>'){
  	  	  			this.$Message.info('修改功能稍后提供');
  	  	  			return;
  	  			}
				$.post('list_handle.php?do=join', {id:this.list[index].id}, function(ret){
					if(ret && ret.success){
						self.$Message.info('加入活动成功');
					}else{
						self.$Message.error('错误：' + ret.msg);
					}
				},'JSON');
            },
	        show_add: function(){
		        if(Object.getOwnPropertyNames(this.my_game).length > 0)this.modal = true;
	        	else window.location = 'setting.php';
	        },
	        add: function(){
		        self = this;
				this.addNew.date = this.addNew.date.Format("yyyy-MM-dd");
		        $.post('list_handle.php?do=add', this.addNew, function(ret){
			        if(ret && ret.success){
				        window.location.reload(true);
			        }else{
				        self.$Message.error('错误：' . ret.msg);
			        }
		        },'JSON');
	        }
  		},
  		computed:{
			no_list: function(){
				if(this.list.length > 0) return false;
				else return true;
			}
  	  	}
  	})
  </script>
</html>