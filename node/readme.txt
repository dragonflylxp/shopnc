node服务器安装说明
1、访问http://nodejs.org/download/，下载对应平台的程序。
2、将目录下文件拷入非web服务器的目录，如所用Apache的PHP环境，
	放到Apache访问不到的目录，防止关键文件泄露。
3、根据实际运行环境修改config.js中相关参数。
4、在命令行模式下用node chat启动程序，看到如下信息表示成功。
	info  - socket.io started
	mysql connected
	
	