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
		<h3>“大家一起来运动”欢迎你！</h3>
		<br/>
		<br/>
		请先：<i-Button @click="modal = true" style="font-size:14px;background: #393D49;color: #fff;">登录/注册</i-Button>
		
		<Modal v-model="modal" width="400">
			<p slot="header" style="color:#f60;text-align:center">
			</p>
			<Row :gutter="32">
				<Col span="24">
				<Tabs>
					<Tab-Pane label="登录">
					<i-Form ref="Login" :model="Login" :rules="ruleInline" class="signin" style="margin-top:30px">
						<Form-Item prop="mobile">
						<i-Input type="text" v-model="Login.mobile" placeholder="输入用户名" >
						<Icon type="ios-person-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text" v-show="Login.error.mobile">{{Login.error.mobile}}</p>
						</Form-Item>
						<Form-Item prop="passwd">
						<i-Input type="password" v-model="Login.passwd" placeholder="输入密码" >
						<Icon type="ios-locked-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text" v-show="Login.error.passwd">{{Login.error.passwd}}</p>
						</Form-Item>
						<Form-Item style="text-align:center">
						<p class="error-text" v-show="Login.error.all">{{Login.error.all}}</p>
						<i-Button type="primary" @click="loginSubmit" style="width:60%;font-size:16px">登录</i-Button>
						</Form-Item>
					</i-Form>
					</Tab-Pane>
					<Tab-Pane label="注册">
					<i-Form ref="Register" :model="Register" :rules="ruleInline" class="signup" style="margin-top:30px">
						<Form-Item prop="mobile">
						<i-Input type="text" v-model="Register.mobile" placeholder="输入用户名" style="text-align:center">
						<Icon type="ios-person-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text marb8" v-show="Register.error.mobile">{{Register.error.mobile}}</p>
						</Form-Item>
						<Form-Item prop="code">
						<div class="flex">
						<i-Input type="text" v-model="Register.code" placeholder="输入获取的验证码" style="text-align:center"></i-Input>
						</div>
						<p class="error-text marb8" v-show="Register.error.code">{{Register.error.code}}</p>
						</Form-Item>
						<Form-Item prop="passwd">
						<i-Input type="password" v-model="Register.passwd" placeholder="输入密码">
						<Icon type="ios-locked-outline" slot="prepend"></Icon>
						</i-Input>
						<p class="error-text marb8" v-show="Register.error.passwd">{{Register.error.passwd}}</p>
						</Form-Item>
						<Form-Item style="text-align:center">
						<p class="error-text marb8" v-show="Register.error.error">{{Register.error.error}}</p>
						<i-Button type="primary" @click="registerSubmit" style="width:60%;font-size:16px">注册</i-Button>
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
			error: {mobile: '请输入用户名', passwd: '请输入密码'}
  	  	},
  		ruleInline: {
			mobile: []
  	  	},
  	  	Register: {
  	  	  	mobile: '',
  	  	  	code: '',
  	  	  	passwd: '',
  	  	  	error: {mobile: '请输入用户名', code: '测试期请输入1234', passwd: '请输入密码'}
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
  	  	  				self.$Message.error("错误：" + ret.msg);
  	  	  	  		}
				},'JSON');
  	  		},
  	  		registerSubmit: function(){
  	  			self = this;
  				$.post('/joinUs/login_handle.php?do=register', this.Register, function(ret){
  	  				if(ret &&  ret.success){
						window.location = 'setting.php';
  	  	  			}else{
  	  	  				self.$Message.error("错误：" + ret.msg);
  	  	  	  		}
				},'JSON');
  	  		}
  		}
  		
  	})
  </script>
</html>