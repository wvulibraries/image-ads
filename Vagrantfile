# -*- mode: ruby -*-
# vi: set ft=ruby :

PROJECT_NAME = "Rotating-Homepage-Ads *Ruby"
API_VERSION  = "2"

Vagrant.configure(API_VERSION) do |config|
    config.vm.define PROJECT_NAME, primary: true do |config|
        config.vm.provider :virtualbox do |vb|
            vb.name = PROJECT_NAME
            vb.customize [ "guestproperty", "set", :id, "/VirtualBox/GuestAdd/VBoxService/--timesync-set-threshold", 1000 ]
        end

        config.vm.box = "bento/centos-7.2"
        config.vm.network :forwarded_port, guest: 3000, host: 3010
        config.vm.network "private_network", ip: "127.0.0.1"
        config.vm.provision "shell", path: "bootstrap.sh"
        config.ssh.insert_key = false
    end
end
