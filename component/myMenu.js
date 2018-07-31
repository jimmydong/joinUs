//模板
var template = function(){/*
	    <i-Menu mode="horizontal" :theme="theme" :active-name="active_name">
	        <Menu-Item name="1" @click.native="menu_go(1)">
	            <Icon type="ios-people"></Icon>
	            附近
	        </Menu-Item>
	        <Menu-Item name="2" @click.native="menu_go(2)">
	            <Icon type="ios-people"></Icon>
	            推荐
	        </Menu-Item>
	        <Menu-Item name="3" @click.native="menu_go(3)">
	            <Icon type="ios-paper"></Icon>
	            日历
	        </Menu-Item>

	        <Menu-Item name="4" @click.native="menu_go(4)">
	            <Icon type="settings"></Icon>
	            设置
	        </Menu-Item>
	    </i-Menu>
*/}.toString().split('\n').slice(1,-1).join('\n') + '\n';

Vue.component('my-menu', {
	template: template,
	props:{
		active_name: {
			default: '1'
		}
	},
	data: function(){
		return {
			theme: 'primary',
			test: 'test'
		}
	},
	methods: {
		menu_go: function(index){
			if(index == this.active_name) return;
			if(index == 1) window.location = "index.php";
			if(index == 2) window.location = "list.php";
			if(index == 3) window.location = "schedule.php";
			if(index == 4) window.location = "setting.php";
		}
	}
})
