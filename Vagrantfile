boxes = [
  {
    :name => 'build.docs',
    :ip => '192.168.188.130',
    :run_list => 'role[site-docstypo3org]',
    :memory => 512,
  },
  # For later use
  #{
  #  :name => 'docs',
  #  :run_list => 'role[site-docstypo3org]',
  #  :ip => '192.168.156.131',
  #  :memory => 512
  #}
]

Vagrant.configure("2") do |config|

  #config.ssh.insert_key = true

  # VAGRANT_BOX (like all the other ENV['...']) is an environment variable,
  # which can be set for the current shell session via
  #   $ export VAGRANT_BOX=wheezy
  # or can be added to your shell profile (e.g. ~/.bash_profile)
  base_box = ENV['VAGRANT_BOX'] || "squeeze"
  domain = ENV['VAGRANT_DOMAIN'] || "typo3.box"

  config.vm.box = base_box
  config.vm.box_url = "https://dl.dropbox.com/u/1467717/VirtualBoxes/squeeze.box"

  # Detects vagrant-cachier plugin (`vagrant plugin install vagrant-cachier`)
  if Vagrant.has_plugin?('vagrant-cachier')
    config.cache.auto_detect = true
    config.cache.scope = :box
    # If you are using VirtualBox, you might want to enable NFS for shared folders
    #config.cache.synced_folder_opts = {
    #  type: :nfs,
    #  mount_options: ['rw', 'vers=3', 'tcp', 'nolock']
    #}
  else
    puts 'WARN:  Vagrant-cachier plugin not detected. Continuing unoptimized.'
  end

  # this requires vagrant-omnibus plugin (`vagrant plugin install vagrant-omnibus`)
  config.omnibus.chef_version = ENV['VAGRANT_CHEF_VERSION'] || :latest

  boxes.each do |opts|
    config.vm.define opts[:name] do |config|

      config.vm.hostname = "%s.#{domain}" % opts[:name].to_s
      config.vm.network :private_network, ip: opts[:ip]
      config.vm.network :forwarded_port, guest: 80, host: opts[:http_port], id: "http" if opts[:http_port]

      memory = opts[:memory] || 512
      cpus = opts[:cpus] || 2

      # VirtualBox is the provider by default
      config.vm.provider :virtualbox do |vbox|
        vbox.gui = opts[:gui] || false

        vbox.customize ["modifyvm", :id, "--memory", memory]
        vbox.customize ["modifyvm", :id, "--cpus", cpus]
        vbox.name = "%s.#{domain}" % opts[:name].to_s
      end

      # In case VMware provider is installed and used
      config.vm.provider :vmware do |vmware|
        vmware.gui = true

        vmware.vmx['memsize'] = memory
        vmware.vmx['numvcpus'] = cpus
        vmware.vmx['displayName'] = "%s.#{domain}" % opts[:name].to_s
      end

      config.vm.provision :chef_solo do |chef|
        chef.cookbooks_path  = ["cookbooks", "site-cookbooks"]
        chef.roles_path      = ["roles"]
        chef.data_bags_path  = ["data_bags"]
        chef.log_level       = ENV['CHEF_LOG_LEVEL'] || :info

        # Define common roles
        run_list = []
        run_list << ['role[debian]']
        run_list << ['role[vagrant]']
        run_list << opts[:run_list].split(",") if opts[:run_list]
        run_list << ENV['CHEF_RUN_LIST'].split(",") if ENV.has_key?('CHEF_RUN_LIST')
        chef.run_list = [run_list].flatten
      end
    end
  end
end
