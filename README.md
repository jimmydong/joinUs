# joinUs
运动爱好者互通预约信息

## 功能演示

基础功能：

+用户注册、登录，设定感兴趣项目的级别（1-10。设置0为不参与）
+按地图查看推荐的活动，按列表查看推荐的活动。（推荐活动原则：级别相同）
+发布自己的活动

待开发功能：

+按活动参与人数限制
+活动开始和结束的时间
+管理自己发布的活动
+用户之间互动交流
+按地域进行推荐

在线演示： http://test.yishengdaojia.cn/joinUs/

安卓APP： http://test.yishengdaojia.cn/joinUs/app/joinUs_1.0.apk

## 环境

推荐： php5.6 + apache2.x 

window请注意： php.ini 中加载 sqlite3.dll

## 说明

首次运行： http://xxxx/joinUs/test.php 进行初始化

初始化自动加入了演示用场地信息、项目信息、人员信息。 请自行开发功能对各项信息进行管理。

为简化代码，未使用MVC结构。（实际应用，可采用熟悉的框架做MVC封装，便于深入改造开发）

为简化操作，使用了sqlite。 （实际应用，应改为MySQL或Mongo存储）

## 参考

vue.js 一套用于构建用户界面的渐进式框架。双向绑定数据模型，超赞！ https://cn.vuejs.org/

iView 适用于vue的高质量UI组件库。界面清晰简洁，非常酷！ https://www.iviewui.com/

HBuilder 不仅仅是一个好用的IDE，更是强大的APP构建工具。 http://www.dcloud.io/



