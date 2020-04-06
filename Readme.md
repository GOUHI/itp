项目名称
简单的中后端项目，打造上手即用。



上手指南
1、熟悉thinkphp
2、熟悉vue+iview
3、熟悉环境搭建



安装要求
1、vue  
node.js 10.0.0+
vue-cli 3.0+
2、php
php 7.2+



安装步骤
1、到admin目录中执行npm install
2、本地项目的话，修改vue.config.js的接口地址（线上项目屏蔽）
3、ng配置反向，不明白的可以查看下方代码
```
# 反向代理配置
location /api {
    proxy_pass http://localhost:8080/api;
    proxy_set_header Host $http_host;
}
```

使用到的框架
Dropwizard - Web框架
Maven - 依赖属性管理
ROME - 生成RSS源



贡献者
李狗嗨


版本控制
1.0



作者
李狗嗨



版权说明
可正常使用



鸣谢
