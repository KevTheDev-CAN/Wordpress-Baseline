#
# Cookbook Name:: wordpress
# Recipe:: default
#
# Copyright (C) 2014 YOUR_NAME
#
# All rights reserved - Do Not Redistribute
#

# package 'lsof' do
#   action :install
# end
#
# package 'htop' do
#   action :install
# end
#
# package 'ncdu' do
#   action :install
# end
#
# package 'rkhunter' do
#   action :install
# end

package 'zip' do
  action :install
end
package 'unzip' do
  action :install
end

execute "install_git" do
  user "root"
  action :run
  command "sudo yum install -y git"
end

execute "install_epel" do
  user "root"
  action :run
  command "sudo yum install -y epel-release"
end

execute "install_remi" do
  user "root"
  action :run
  command "sudo rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm"
end

 execute "setup_latest_mariadb_repo" do
   user "root"
   action :run
   command "echo '
# MariaDB 10.2 CentOS repository list - created 2017-10-04 17:21 UTC
# http://downloads.mariadb.org/mariadb/repositories/
[mariadb]
name = MariaDB
enabled=1
baseurl = http://yum.mariadb.org/10.2/centos7-amd64
gpgkey=https://yum.mariadb.org/RPM-GPG-KEY-MariaDB
gpgcheck=1' > /etc/yum.repos.d/mariadb.repo"
 end

package 'mariadb-server' do
  action :install
end

execute "set_mariadb_max_allowed_packet" do
  user "root"
  action :run
  command "echo '[mariadb]
max_allowed_packet=500M' >> /etc/my.cnf"
end

service 'mariadb' do
  action [ :enable, :start ]
end

execute "set_mariadb_root_password" do
  user "root"
  action :run
  command "mysqladmin -u root password "+node[:mysql][:server_root_password]
end

execute 'create-database' do
  command "echo 'create database "+node[:mysql][:database_name]+"' | mysql -uroot -p#{node[:mysql][:server_root_password]}"
end

execute 'restore-database' do
  command "zcat /vagrant/db.sql.gz | mysql -uroot -p#{node[:mysql][:server_root_password]} "+node[:mysql][:database_name]
  only_if { ::File.exists?('/vagrant/db.sql.gz') }
end

execute "install_nginx" do
  user "root"
  action :run
  command "sudo rpm -Uvh http://nginx.org/packages/centos/7/x86_64/RPMS/nginx-1.12.2-1.el7_4.ngx.x86_64.rpm"
end

cookbook_file "/etc/nginx/nginx.conf" do
  source 'nginx.conf'
end

service 'nginx' do
  action [ :enable, :start ]
end

execute "install_php" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php --enablerepo=remi-php73"
end

execute "install_phpfpm" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-fpm --enablerepo=remi-php73"
end

execute "install_php_mysqldnd" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-mysqlnd --enablerepo=remi-php73"
end

execute "install_php_gd" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-gd --enablerepo=remi-php73"
end

execute "install_php_curl" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-curl --enablerepo=remi-php73"
end

execute "install_php_mbstring" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-mbstring --enablerepo=remi-php73"
end

execute "install_php_dom" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-dom --enablerepo=remi-php73"
end

execute "install_php_opcache" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-opcache --enablerepo=remi-php73"
end

execute "install_php_soap" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y php-soap --enablerepo=remi-php73"
end

execute "set_php_display_errors" do
  user "root"
  action :run
  command "sudo sed -i.bak s/'display_errors = Off'/'display_errors = On'/g /etc/php.ini"
end
execute "set_php_display_startup_errors" do
  user "root"
  action :run
  command "sudo sed -i.bak s/'display_startup_errors = Off'/'display_startup_errors = On'/g /etc/php.ini"
end

execute "set_php_memory" do
  user "root"
  action :run
  command "sudo sed -i.bak s/'memory_limit = 128M'/'memory_limit = 900M'/g /etc/php.ini"
end

service 'php-fpm' do
  action [ :enable, :start ]
end

execute "get_composer" do
  cwd "/home/vagrant"
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y composer --enablerepo=remi-php73"
end

execute "install_java" do
  user "vagrant"
  action :run
  environment ({'HOME' => '/home/vagrant', 'USER' => 'vagrant'})
  command "sudo yum install -y java-1.8.0-openjdk"
end

#https://www.nginx.com/blog/nginx-se-linux-changes-upgrading-rhel-6-6/
execute "configure_selinux_for_nginx" do
  user "root"
  action :run
  command "semanage permissive -a httpd_t"
end
