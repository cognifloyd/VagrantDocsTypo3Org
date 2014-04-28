#!/bin/bash
######################################
# Handle application download
######################################

# Move to the expected directory
cd build.docs.typo3.org

# If file "flow" is found means the application has already been installed.
if [ ! -f flow ]; then

	php=`which php`

	if [ ! -f composer.phar ]; then
		curl -sS https://getcomposer.org/installer | $php
	fi

	$php composer.phar install
fi
