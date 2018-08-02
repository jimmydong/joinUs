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
	    <h3>Setting</h3>
    	<i-Input v-model="nickname" style="width:300px">
    		<span slot="prepend">My nickname</span>
    	</i-Input>
    	<h3>Gender</h3>
    	<Radio-Group v-model="gender">
	        <Radio label="0"><Icon type="body"></Icon><span>secret</span></Radio>
	        <Radio label="1"><Icon type="female"></Icon><span>female</span></Radio>
	        <Radio label="2"><Icon type="male"></Icon><span>male</span></Radio>
	    </Radio-Group>
  		<br/>
  		<br/>
	    <h3>rank(0-10)</h3>
    	<div v-for="item,index in my_rank" :key="index">
	    	<i-Input v-model="my_rank[index]" style="width:100px">
	    		<span slot="prepend">{{index}}</span>
	    	</i-Input>
	    </div>
	    *If you do not like a game, set it to 0. 
  		<br/>
  		<hr/>
  		<br/>
  		<i-Button type="primary" icon="plus" @click="submit">Save</i-Button>
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
						self.$Message.info('Saved. You can go to the first page');
					}else{
						self.$Message.error("Error: " + ret.msg);
					}
				},'JSON');
  	  		}
  		}
  		
  	})
  </script>
</html>