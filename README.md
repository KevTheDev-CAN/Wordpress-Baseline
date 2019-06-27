# Wordpress
Baseline project setup for a wordpress site using bedrock https://roots.io/bedrock/.

When starting a new wordpress project download this repository.

## Dependencies

Virtual Box https://www.virtualbox.org/wiki/Downloads

ChefDK https://downloads.chef.io/chefdk

Vagrant https://www.vagrantup.com/

> Requires vbguest plugin ``vagrant plugin install vagrant-vbguest``
> Requires vagrant-env plugin ``vagrant plugin install vagrant-env``

NodeJS https://nodejs.org/en/

GulpJS https://gulpjs.com/
```
npm install gulp-cli -g
```

## Changes required to get up and running

### Update IP and/or Port numbers in Vagrantfile (Optional)

```ruby
  config.vm.network :private_network, ip: '172.16.231.130'
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.network :forwarded_port, guest: 9200, host: 9201
```

### Setup project .env

Navigate to /src/ and duplicate .env.example. Rename it to .env.

Set your environment variables

```
DB_USER=root
DB_PASSWORD=root
WP_HOME=http://172.16.231.130 #(Or whatever IP you custom configure in your vagrantfile)
```

## Running your development environment

- Navigate to your project folder in command line
- Run `vagrant up` (this will take some time to finish)
- Run `vagrant ssh` (this will log you into the virtual box machine)
- Run `cd /vagrant/src && composer install` (this will install all project dependencies)
- Run `composer update` to then get the latest versions (once confirming the site basically works) (this will take some time to finish)
- from your machines cli at the root of your project, `npm install`. (this will install all project dependencies)
- Then `gulp` (This will compile your theme assets)

You should now be able to reach your site using the IP address found in your vagrantfile

### Updating your database

- Run `vagrant ssh` (this will log you into the virtual box machine)
- Run `cd /vagrant/ && zcat db.sql.gz | mysql -uroot -proot wordpress`
