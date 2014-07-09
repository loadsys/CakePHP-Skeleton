require 'yaml'

# Provides a mechanism to relocate the puphpet folder more easily. -bp
configDir = File.dirname(File.expand_path(__FILE__)) + '/Lib/puphpet'
configDir = './Lib/puphpet'
configValues = YAML.load_file("#{configDir}/config.yaml")
data = configValues['vagrantfile-local']

Vagrant.configure("2") do |config|
  config.vm.box = "#{data['vm']['box']}"
  config.vm.box_url = "#{data['vm']['box_url']}"

  # Enable vagrant-cachier plugin for apt package caching on the host. -bp
  if Vagrant.has_plugin?("vagrant-cachier")
    #config.cache.auto_detect = true
    # If you are using VirtualBox, you might want to enable NFS for shared folders
    # config.cache.enable_nfs  = true
  end

  if data['vm']['hostname'].to_s != ''
    config.vm.hostname = "#{data['vm']['hostname']}"
  end

  # Provides a way for the VM to be accessible from beyond the host machine. -bp
  if data['vm']['network']['public_network'].to_s != ''
    config.vm.network "public_network", bridge: "#{data['vm']['network']['public_network']}"
  elsif data['vm']['network']['public_network']
    config.vm.network "public_network"  # Will prompt for the host machine's interface to bridge to.
  elsif data['vm']['network']['private_network'].to_s != ''
    config.vm.network "private_network", ip: "#{data['vm']['network']['private_network']}"
  end

  data['vm']['network']['forwarded_port'].each do |i, port|
    if port['guest'] != '' && port['host'] != ''
      config.vm.network :forwarded_port, guest: port['guest'].to_i, host: port['host'].to_i
    end
  end

  data['vm']['synced_folder'].each do |i, folder|
    if folder['source'] != '' && folder['target'] != ''
      nfs = (folder['nfs'] == "true") ? "nfs" : nil
      if nfs == "nfs"
        config.vm.synced_folder "#{folder['source']}", "#{folder['target']}", id: "#{i}", type: nfs
      else
        config.vm.synced_folder "#{folder['source']}", "#{folder['target']}", id: "#{i}", type: nfs,
          group: 'www-data', user: 'www-data', mount_options: ["dmode=775", "fmode=764"]
      end
    end
  end

  config.vm.usable_port_range = (10200..10500)

  # A pre-set env var takes precedence over the config.yaml file to allow per-developer overrides. -bp
  unless ENV.fetch('VAGRANT_DEFAULT_PROVIDER', '').strip.empty?
  	data['vm']['chosen_provider'] = ENV['VAGRANT_DEFAULT_PROVIDER'];
  end

  if data['vm']['chosen_provider'].empty? || data['vm']['chosen_provider'] == "virtualbox"
    ENV['VAGRANT_DEFAULT_PROVIDER'] = 'virtualbox'

    config.vm.provider :virtualbox do |virtualbox|
      data['vm']['provider']['virtualbox']['modifyvm'].each do |key, value|
        if key == "memory"
          next
        end

        if key == "natdnshostresolver1"
          value = value ? "on" : "off"
        end

        virtualbox.customize ["modifyvm", :id, "--#{key}", "#{value}"]
      end

      virtualbox.customize ["modifyvm", :id, "--memory", "#{data['vm']['memory']}"]
      virtualbox.customize ["modifyvm", :id, "--name", config.vm.hostname]  # Set the VM container's name. -bp
    end
  end

  if data['vm']['chosen_provider'] == "vmware_fusion" || data['vm']['chosen_provider'] == "vmware_workstation"
    ENV['VAGRANT_DEFAULT_PROVIDER'] = (data['vm']['chosen_provider'] == "vmware_fusion") ? "vmware_fusion" : "vmware_workstation"

    config.vm.provider "vmware_fusion" do |v|
      data['vm']['provider']['vmware'].each do |key, value|
        if key == "memsize"
          next
        end

        v.vmx["#{key}"] = "#{value}"
      end

      v.vmx["memsize"] = "#{data['vm']['memory']}"
      v.vmx["displayName"] = config.vm.hostname  # Set the VM container's name. -bp
    end
  end

  ssh_username = !data['ssh']['username'].nil? ? data['ssh']['username'] : "vagrant"

  config.vm.provision "shell" do |s|
    s.path = "#{configDir}/shell/initial-setup.sh"
    s.args = "/vagrant/#{configDir}"
  end
  config.vm.provision "shell" do |kg|
    kg.path = "#{configDir}/shell/ssh-keygen.sh"
    kg.args = "#{ssh_username}"
  end
  config.vm.provision :shell, :path => "#{configDir}/shell/update-puppet.sh"

  config.vm.provision :puppet do |puppet|
    puppet.facter = {
      "ssh_username"     => "#{ssh_username}",
      "provisioner_type" => ENV['VAGRANT_DEFAULT_PROVIDER'],
      "vm_target_key"    => 'vagrantfile-local',
    }
    puppet.manifests_path = "#{data['vm']['provision']['puppet']['manifests_path']}"
    puppet.manifest_file = "#{data['vm']['provision']['puppet']['manifest_file']}"
    puppet.module_path = "#{data['vm']['provision']['puppet']['module_path']}"

    if !data['vm']['provision']['puppet']['options'].empty?
      puppet.options = data['vm']['provision']['puppet']['options']
    end
  end

  config.vm.provision :shell, :path => "#{configDir}/shell/execute-files.sh"
  config.vm.provision :shell, :path => "#{configDir}/shell/important-notices.sh"

  if File.file?("#{configDir}/files/dot/ssh/id_rsa")
    config.ssh.private_key_path = [
      "#{configDir}/files/dot/ssh/id_rsa",
    ]
  end

  if !data['ssh']['host'].nil?
    config.ssh.host = "#{data['ssh']['host']}"
  end
  if !data['ssh']['port'].nil?
    config.ssh.port = "#{data['ssh']['port']}"
  end
  if !data['ssh']['username'].nil?
    config.ssh.username = "#{data['ssh']['username']}"
  end
  if !data['ssh']['guest_port'].nil?
    config.ssh.guest_port = data['ssh']['guest_port']
  end
  if !data['ssh']['shell'].nil?
    config.ssh.shell = "#{data['ssh']['shell']}"
  end
  if !data['ssh']['keep_alive'].nil?
    config.ssh.keep_alive = data['ssh']['keep_alive']
  end
  if !data['ssh']['forward_agent'].nil?
    config.ssh.forward_agent = data['ssh']['forward_agent']
  end
  if !data['ssh']['forward_x11'].nil?
    config.ssh.forward_x11 = data['ssh']['forward_x11']
  end
  if !data['vagrant']['host'].nil?
    config.vagrant.host = data['vagrant']['host'].gsub(":", "").intern
  end

end

