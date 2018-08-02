<?php 
include('include.inc.php');
show_header();
?>


<style>
/这里加上scoped就不起作用/
.ivu-tabs-nav {
left: 50%;
transform: translateX(-50%);
font-size: 20px;
}
/手机端模态框/
.qqwximg {
height: 38px;
}
.error-text {
color: #fa8341;
}
.ivu-tabs-tabpane{
	padding: 20px 40px;
}
</style>
<div id=app>
	<div class="container">
		<h3>Welcome: "Join Us to sport"</h3>
		<br/>
		<br/>
		Please: <i-Button @click="modal = true" style="font-size:14px;background: #393D49;color: #fff;">Login/Regist</i-Button>
		
		<Modal v-model="modal" width="400">
			<p slot="header" style="color:#f60;text-align:center">
			</p>
			<Row :gutter="32">
				<Col span="24">
				<Tabs>
					<Tab-Pane label="Login">
					<i-Form ref="Login" :model="Login" :rules="ruleInline" class="signin" style="margin-top:30px">
						<Form-Item prop="mobile">
						<i-Input type="text" v-model="Login.mobile" placeholder="name" >
						<Icon type="ios-person-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text" v-show="Login.error.mobile">{{Login.error.mobile}}</p>
						</Form-Item>
						<Form-Item prop="passwd">
						<i-Input type="password" v-model="Login.passwd" placeholder="password" >
						<Icon type="ios-locked-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text" v-show="Login.error.passwd">{{Login.error.passwd}}</p>
						</Form-Item>
						<Form-Item style="text-align:center">
						<p class="error-text" v-show="Login.error.all">{{Login.error.all}}</p>
						<i-Button type="primary" @click="loginSubmit" style="width:60%;font-size:16px">Login</i-Button>
						</Form-Item>
					</i-Form>
					</Tab-Pane>
					<Tab-Pane label="Regist">
					<i-Form ref="Register" :model="Register" :rules="ruleInline" class="signup" style="margin-top:30px">
						<Form-Item prop="mobile">
						<i-Input type="text" v-model="Register.mobile" placeholder="name" style="text-align:center">
						<Icon type="ios-person-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text marb8" v-show="Register.error.mobile">{{Register.error.mobile}}</p>
						</Form-Item>
						<Form-Item prop="code">
						<div class="flex">
						<i-Input type="text" v-model="Register.code" placeholder="valid code" style="text-align:center"></i-Input>
						</div>
						<p class="error-text marb8" v-show="Register.error.code">{{Register.error.code}}</p>
						</Form-Item>
						<Form-Item prop="passwd">
						<i-Input type="password" v-model="Register.passwd" placeholder="password">
						<Icon type="ios-locked-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text marb8" v-show="Register.error.passwd">{{Register.error.passwd}}</p>
						</Form-Item>
						<Form-Item style="text-align:center">
						<p class="error-text marb8" v-show="Register.error.error">{{Register.error.error}}</p>
						<i-Button type="primary" @click="registerSubmit" style="width:60%;font-size:16px">Regist</i-Button>
						</Form-Item>
					</i-Form>
					</Tab-Pane>
				</Tabs>
				</Col>
			</Row>
			<div slot="footer" style="text-align:center">
				<p></p>
			</div>
		</Modal>
	</div>
</div>

  </body>
  <script>
  	var data = {
  		modal: true,
  		Login: {
			mobile: '',
			code: '',
			error: {mobile: 'please input name', passwd: 'please input password'}
  	  	},
  		ruleInline: {
			mobile: []
  	  	},
  	  	Register: {
  	  	  	mobile: '',
  	  	  	code: '',
  	  	  	passwd: '',
  	  	  	error: {mobile: 'please input name', code: 'please input: 1234', passwd: 'please input password'}
  	  	}
  	}

  	var app = new Vue({
  		el: '#app',
  		data: data,
  		methods:{
  			loginSubmit: function(){
  	  			self = this;
  				$.post('/joinUs/login_handle.php?do=login', this.Login, function(ret){
  	  				if(ret &&  ret.success){
						window.location = 'index.php';
  	  	  			}else{
  	  	  				self.$Message.error("Error: " + ret.msg);
  	  	  	  		}
				},'JSON');
  	  		},
  	  		registerSubmit: function(){
  	  			self = this;
  				$.post('/joinUs/login_handle.php?do=register', this.Register, function(ret){
  	  				if(ret &&  ret.success){
						window.location = 'setting.php';
  	  	  			}else{
  	  	  				self.$Message.error("Error: " + ret.msg);
  	  	  	  		}
				},'JSON');
  	  		}
  		}
  		
  	})
  </script>
</html>