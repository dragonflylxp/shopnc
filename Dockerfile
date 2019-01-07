#基础镜像 
FROM linode/lamp 

#维护信息
MAINTAINER lixiaopeng dragonflylxp@gmail.com 

#添加apache启动用户
RUN useradd apache
RUN mkdir -p /var/log/shopnc

#安装PHP扩展
RUN apt-get update
RUN apt-get install -y php5-curl
RUN apt-get install -y php5-mysql
RUN apt-get install -y php5-gd
RUN apt-get install -y php5-mcrypt
RUN ln -s /etc/php5/mods-available/mcrypt.ini /etc/php5/apache2/conf.d/20-mcrypt.ini

#安装nodejs
RUN apt-get install -y wget
RUN apt-get install -y gcc g++
RUN apt-get install -y make
RUN apt-get install -y python 
RUN wget http://nodejs.org/dist/v0.10.25/node-v0.10.25.tar.gz
RUN tar zxf node-v0.10.25.tar.gz
WORKDIR /node-v0.10.25
RUN ./configure
RUN make && make install

#添加crontab任务
RUN touch /var/spool/cron/crontabs/root
COPY ./src/shopnc/crontab/shopnc /var/spool/cron/crontabs/root 

#安装supervisor
RUN apt-get install -y python-pip
RUN /usr/bin/pip install supervisor

#执行命令
ENTRYPOINT ["/var/www/shopnc/entrypoint.sh"]
