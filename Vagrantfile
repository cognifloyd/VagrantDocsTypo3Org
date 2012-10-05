# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant::Config.run do |config|
  config.ssh.max_tries = 100

  config.vm.box = "squeeze"
  config.vm.box_url = "https://dl.dropbox.com/u/1467717/VirtualBoxes/squeeze.box"
  config.vm.boot_mode = :gui
  
  # Network
  config.vm.host_name = 'docs.typo3.dev'
  config.vm.network :hostonly, "192.168.156.131"

  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path  = ["cookbooks", "site-cookbooks"]
    chef.roles_path      = ["roles"]
    chef.data_bags_path  = ["data_bags"]

    # Turn on verbose Chef logging if necessary
    #chef.log_level      = :debug

    # Apply the recipies you are going to work on/need.
    run_list = []
    run_list << ['recipe[minitest-handler]']
    run_list << ['role[debian]']
    run_list << ['role[vagrant]']
    run_list << ['role[site-docs]']
    run_list << ENV['CHEF_RUN_LIST'].split(",") if ENV.has_key?('CHEF_RUN_LIST')
    chef.run_list = [run_list].flatten
  end

  config.vm.customize [
    "modifyvm", :id,
    "--name", "docs.typo3.dev",
    "--memory", "512"
  ]
end
