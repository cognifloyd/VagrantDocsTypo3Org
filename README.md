# Overview

This is a repository used for setting up a development version of docs.typo3.org website.

For the first installation, consider one good hour to walk through all the steps which will depend on the speed of your network along to the the performance of your machine. There will about 500 Mb to download which includes a virtual machine and the necessary packages.

# Quick set up guide

The first commands will check if the needed software is installed.

	# Test if your system contains all the necessary software.
	vagrant help        -> Refer to "Vagrant" and "Virtualbox" chapter if command missing
	bundle help         -> Refer to "Gems" chapter if command missing

	# Add a new image that will be stored into ~/.vagrant.d/boxes/
	vagrant box add squeeze https://dl.dropbox.com/u/1467717/VirtualBoxes/squeeze.box

	# Install Gem dependencies
	bundle install

	# Download and install the necessary cookbooks
	librarian-chef install
	
	# Fire up the Virtual Machine... this may take some time
	vagrant up

	# Once the machine is set up you can enter the virtual machine by using vagrant itself.
	vagrant ssh
	
	# Alternatively, you can access through ssh
	# The default username / password is vagrant / vagrant
	ssh vagrant@192.168.156.131

	# Time to play with the application
	cd /var/www/vhosts/docs.typo3.org/releases/current
	./flow3 help
	
	-> check the commands "document:*" exist
	
	# Update the database
	cat Configuration/Development/Settings.yaml
	
	# Update the Schema
	./flow3 doctrine:update

	# Prepare 10 documents from Git and 10 from TER
	./flow3 document:renderall --limit 10 git 
	./flow3 document:renderall --limit 10 ter
	
	# Process the queue 
	# Notice: normally this command should be run in another terminal within a screen
	./flow3 job:work git

	# Sugestion to access the virtual machine from outside
	open Vagrantfile
	# -> comment out "config.vm.network" line
	vagrant reload

	# Files system is at:
	cd /var/www/vhost/docs.typo3.dev

Refer to the troubleshooting section below if any problem pops up during installation.

# Installation of the software

## Vagrant

Vagrant can be downloaded and installed from the website http://vagrantup.com/

	# Run as sudo if permission issue
	gem update --system
	gem install vagrant

## Virtualbox

VirtualBox is a powerful x86 and AMD64/Intel64 virtualization product for enterprise as well as home use.
Follow this download link to install it on your system https://www.virtualbox.org/wiki/Downloads

## Gems

Bundler manages an application's dependencies through its entire life across many machines systematically and repeatably.

	# Run as sudo if permission issue
	gem update --system
	gem install bundler --no-ri --no-rdoc

# Configure Vagrant file

To adjust configuration open ``Vagrantfile`` file and change settings according to your needs.

	# Define IP of the virtual machine to access it from the host
	config.vm.network :hostonly, "192.168.156.122"

	# Activate the GUI or run in headless mode
	config.vm.boot_mode = :gui

	# Turn on verbose Chef logging if necessary
	chef.log_level      = :debug

## Troubleshooting

Do you have some?
