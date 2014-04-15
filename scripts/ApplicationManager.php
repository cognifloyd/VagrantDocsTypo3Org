<?php

/**
 * Handle the source of Flow application.
 */
class ApplicationManager {

	/**
	 * Download application source.
	 *
	 * @return void
	 */
	public function download() {

		if (!$this->isApplicationInstalled()) {

			$this->downloadComposer();

			// Download source
			$command = sprintf('cd %s; php composer.phar install', $this->getDirectory());
			exec($command);
		}
	}

	/**
	 * @return void
	 */
	protected function downloadComposer() {
		if (!$this->isComposerInstalled()) {
			echo 'Downloading Composer' . chr(10);
			$command = sprintf('cd %s; curl -sS https://getcomposer.org/installer | php', $this->getDirectory());
			exec($command);
		}
	}

	/**
	 * @return string
	 */
	protected function isApplicationInstalled() {
		return file_exists($this->getDirectory() . '/flow');
	}

	/**
	 * @return string
	 */
	protected function isComposerInstalled() {
		return file_exists($this->getDirectory() . '/composer.phar');
	}

	/**
	 * @return string
	 */
	protected function getDirectory() {
		return 'build.docs.typo3.org';
	}
}