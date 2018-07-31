<?php 
include('include.inc.php');
$me = check_login();
show_header();

//我的运动等级
$game_list = Util::getGameList();
$tmp_rank = json_decode(stripcslashes($me['json_rank']), true);
$my_rank = [];
foreach($game_list as $game_id=>$game_info){
	$my_rank[$game_info['name']] = $tmp_rank[$game_id]?:0;
}

?>
  	<div id="app" class="container">
  		<my-menu active_name="4"></my-menu>
	    <br>
	    <h3>个人设置</h3>
    	<i-Input v-model="nickname" style="width:300px">
    		<span slot="prepend">我的昵称</span>
    	</i-Input>
    	<h3>性别设定</h3>
    	<Radio-Group v-model="gender">
	        <Radio label="0"><Icon type="body"></Icon><span>保密</span></Radio>
	        <Radio label="1"><Icon type="female"></Icon><span>女生</span></Radio>
	        <Radio label="2"><Icon type="male"></Icon><span>男生</span></Radio>
	    </Radio-Group>
  		<br/>
  		<br/>
	    <h3>等级设定（0-10）</h3>
    	<div v-for="item,index in my_rank" :key="index">
	    	<i-Input v-model="my_rank[index]" style="width:100px">
	    		<span slot="prepend">{{index}}</span>
	    	</i-Input>
	    </div>
	    *注：设置为0表示对该活动不感兴趣
  		<br/>
  		<hr/>
  		<br/>
  		<i-Button type="primary" icon="plus" @click="submit">保存设置</i-Button>
  	</div>
  	
  </body>
  <script src="component/myMenu.js"></script>
	
  <script>
  	var data = {
  		nickname: '<?php echo $me['nickname'];?>',
  		gender: '<?php echo $m['gender']?:'0';?>',
  		my_rank: <?php echo json_encode($my_rank);?>
  	}

  	var app = new Vue({
  		el: '#app',
  		data: data,
  		methods:{
  			submit: function(){
				self = this;
				$.post("setting_handle.php", {nickname:this.nickname, gender:this.gender, my_rank:this.my_rank}, function(ret){
					if(ret && ret.success){
						self.$Message.info('已经保存');
					}else{
						self.$Message.error("错误：" + ret.msg);
					}
				},'JSON');
  	  		}
  		}
  		
  	})
  </script>
</html>