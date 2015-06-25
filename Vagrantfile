# Vagrant init file for Loadsys Cake 3 Skeleton.
#
# Loads configs from `config/provision.yaml`, but
#
# Recommended vagrant plugins:
#   - vagrant-bindfs
#   - vagrant-cachier
#   - vagrant-vbguest
#   - vagrant-vmware-fusion


# Load the provisioning config file.
require 'yaml'
dir = File.dirname(File.expand_path(__FILE__))
configFile = "#{dir}/config/provision.yaml"
conf = YAML.load_file(configFile).fetch('vm', {}) rescue {}
confMd5 = Digest::MD5.hexdigest(File.read(configFile)) rescue 'default'
defaults = {
  "box" => "hashicorp/precise64",
  "hostname" => 'loadsys-cake3skel-' + confMd5,
  "ip" => '192.168.133.42',
  "cpus" => 1,
  "memory" => 512,
  "gui" => false,
  "port_start" => 10200,
  "port_end" => 10500,
}

Vagrant.require_version '>= 1.6.0'
Vagrant.configure(2) do |config|

  netConf = conf.fetch('network', {})

  # Basic VM config
  config.vm.box = conf.fetch('box', defaults['box']).to_s
  config.vm.hostname = conf.fetch('hostname', defaults['hostname']).to_s
  config.vm.network "private_network", ip: netConf.fetch('ip', defaults['ip']).to_s
  config.vm.post_up_message = conf.fetch('post_up_message', nil)

  # Port forwards.
  netConf.fetch('forwarded_port', []).each do |id, port|
    if port.fetch('guest', false) && port.fetch('host', false)
      config.vm.network :forwarded_port,
      	guest: port['guest'].to_i,
      	host: port['host'].to_i,
      	auto_correct: !!netConf.fetch('auto_correct_collisions', true)
    end
  end

  portRange = netConf.fetch('usable_port_range', {})
  config.vm.usable_port_range = (portRange.fetch('start', defaults['port_start']).to_i..portRange.fetch('stop', defaults['port_end']).to_i)

  # Synced folders.
  conf.fetch('synced_folder', []).each do |id, folder|
    if folder.fetch('source', false) && folder.fetch('target', false)
      # Ref: https://github.com/puphpet/puphpet/wiki/Shared-Folder:-Permission-Denied -bp
      config.vm.synced_folder folder['source'].to_s, folder['target'].to_s,
        id: id.to_s,
       	type: 'nfs',
       	:linux__nfs_options => ['rw', 'no_root_squash', 'no_subtree_chec']

        if Vagrant.has_plugin?('vagrant-bindfs')
          config.bindfs.bind_folder folder['target'].to_s, "/mnt/vagrant-#{id}"
        end
    end
  end

  # Virtualbox-specific configs.
  config.vm.provider 'virtualbox' do |vb|
    vb.customize ['modifyvm', :id, '--cpus', conf.fetch('cpus', defaults['cpus']).to_i]
    vb.customize ["modifyvm", :id, "--memory", conf.fetch('memory', defaults['memory']).to_i]
    vb.customize ["modifyvm", :id, "--name", conf.fetch('hostname', defaults['hostname']).to_s]
    vb.gui = !!conf.fetch('gui', defaults['gui'])
  end

  # VMware-specific configs.
  config.vm.provider 'vmware_fusion' do |vm|
    vm.vmx['numvcpus'] = conf.fetch('cpus', defaults['cpus']).to_i
    vm.vmx["memsize"] = conf.fetch('memory', defaults['memory']).to_i
    vm.vmx["displayName"] = conf.fetch('hostname', defaults['hostname']).to_s
    vm.gui = !!conf.fetch('gui', defaults['gui'])
  end

  # Cachier setup.
  if Vagrant.has_plugin?('vagrant-cachier')
    config.cache.scope = :box
    config.cache.synced_folder_opts = {
      type: :nfs,
      mount_options: ['rw', 'nolock']
    }
  end

  # Provisioning.
  config.vm.provision "shell", inline: <<-SHELL
    echo '/vagrant/provision' > /etc/provision_path
    chmod a+r /etc/provision_path
  SHELL

  provConf = conf.fetch('provision', {})
  provConf.fetch('scripts', []).each do |id, script|
	config.vm.provision "shell", path: script["path"], args: script.fetch("args", [])
  end

end
