# -*- mode: ruby -*-
# vi: set ft=ruby :

# All Vagrant configuration is done below. The "2" in Vagrant.configure
# configures the configuration version (we support older styles for
# backwards compatibility). Please don't change it unless you know what
# you're doing.
Vagrant.configure(2) do |config|
  # The most common configuration options are documented and commented below.
  # For a complete reference, please see the online documentation at
  # https://docs.vagrantup.com.

  # Every Vagrant development environment requires a box. You can search for
  # boxes at https://atlas.hashicorp.com/search.
  config.vm.box = "bento/centos-7.4"
  config.vm.hostname = 'wordpress'

  # Create a forwarded port mapping which allows access to a specific port
  # within the machine from a port on the host machine. In the example below,
  # accessing "localhost:8080" will access port 80 on the guest machine.
  config.vm.network :private_network, ip: '172.16.231.130'
  config.vm.network :forwarded_port, guest: 80, host: 8080
  config.vm.network :forwarded_port, guest: 9200, host: 9201

  if Vagrant.has_plugin?("vagrant-omnibus")
    config.omnibus.chef_version = 'latest'
  end

  config.ssh.insert_key = false
  config.vm.synced_folder ".", "/vagrant", type: "nfs", mount_options:['nolock,vers=3,udp,noatime,actimeo=1']

  config.vm.provider "virtualbox" do |v|
    v.memory = 2048
  end

  config.vbguest.auto_update = false

  config.vm.provision :shell, :inline => "ulimit -n 4048"

  config.vm.provision "chef_solo" do |chef|
    # Currently a bug in vagrant when using chef. This is a temporary workaround
    # https://github.com/hashicorp/vagrant/issues/10856
    chef.custom_config_path = "CustomConfiguration.chef"
    chef.run_list = [
      "recipe[wordpress]"
    ]
  end

end
