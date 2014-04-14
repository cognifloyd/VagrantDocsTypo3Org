h1. Overview

This is a repository used for setting up a development version of docs.typo3.org website.

For the first installation, consider one good hour to walk through all the steps which will depend on the speed of your network along to the the performance of your machine. There will about 500 Mb to download which includes a virtual machine and the necessary packages.

h1. Quick set up guide

<pre>

	# Get the source
	git clone git://git.typo3.org/Teams/Server/Vagrant/DocsTypo3Org.git

	# Test if your system contains all the necessary software.
	# "gem" is used to install package (like pear) and is sometimes compiling the package to a native extension.
	# Make sure to have the necessary software installed to compile e.g. "make" and "configure" command must be available
	gem --help          -> Install Ruby if missing
	vagrant --help      -> Refer to "Vagrant" and "Virtualbox" chapter if command missing
	bundle help         -> Refer to "Gems" chapter if command missing

	# Install Gem dependencies
	cd DocsTypo3Org
	bundle install

	# Download and install the necessary cookbooks
	librarian-chef install
	# /var/lib/gems/1.8/bin/librarian-chef install   # on Ubuntu 12.04

	# Fire up the Virtual Machine... this may take some time
	vagrant up

	# Once the machine is set up you can enter the virtual machine by using vagrant itself.
	vagrant ssh

	# Alternatively, you can access through ssh
	# The default username / password is vagrant / vagrant
	ssh vagrant@192.168.156.131

	# Time to play with the application
	sudo su - builddocstypo3org
	export FLOW_CONTEXT=Development/Vagrant
	cd /var/www/vhosts/build.docs.typo3.org/releases/current
	./flow help

	-> check the commands "document:*" exist

	# Update the Schema
	./flow doctrine:migrate

	# Prepare 10 documents from Git and 10 from TER
	# Notice argument "git" and "ter" are optional. If omitted, both TER and Git will be assumed
	./flow document:importall --limit 10 git
	./flow document:importall --limit 10 ter

	# Process the queue
	# Notice: normally this command should be run in another terminal within a screen
	./flow job:work git


	# Process the queue
	./flow queue:start
</pre>

h1. Installation of the software

h2. Vagrant

Vagrant can be downloaded and installed from the website http://www.vagrantup.com/downloads.html

h2. Virtualbox

VirtualBox is a powerful x86 and AMD64/Intel64 virtualization product for enterprise as well as home use.
Follow this download link to install it on your system https://www.virtualbox.org/wiki/Downloads

h2. Gems

Bundler manages an application's dependencies through its entire life across many machines systematically and repeatably.

<pre>
	# Run as sudo if permission issue
	gem update --system
	gem install bundler --no-ri --no-rdoc
</pre>

h1. Configure Vagrant file

To adjust configuration open ``Vagrantfile`` file and change settings according to your needs.

<pre>
	# Define IP of the virtual machine to access it from the host
	config.vm.network :private_network, "192.168.156.122"

	# Turn on verbose Chef logging if necessary
	chef.log_level      = :debug
</pre>

h2. Troubleshooting

h3. Installing librarian-chef fails with Xcode 5.1

If you see an error message like this:

@clang: error: unknown argument: '-multiply_definedsuppress' [-Wunused-command-line-argument-hard-error-in-future]@

then there is a fix for this:

@curl https://gist.githubusercontent.com/Paulche/9713531/raw/1e57fbb440d36ca5607d1739cc6151f373b234b6/gistfile1.txt | sudo patch /System/Library/Frameworks/Ruby.framework/Versions/2.0/usr/lib/ruby/2.0.0/universal-darwin13/rbconfig.rb@