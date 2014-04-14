<?php

/**
 * Manage Cookbook from Opscode - http://community.opscode.com
 */
class CookbookOpscode {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @param string $name
	 * @param string $version
	 */
	public function __construct($name, $version) {
		$this->name = $name;
		$this->version = $version;
	}

	/**
	 * Tell whether the current cookbook is dirty
	 *
	 * @return bool
	 */
	public function isDirty() {
		$isDirty = TRUE;

		// Check whether the cookbook must be updated
		if (file_exists($this->getMetadataFile())) {
			$metadataContent = file_get_contents($this->getMetadataFile());
			$metadata = json_decode($metadataContent, true);

			if (!empty($metadata['version'])) {
				$versionCorresponds = version_compare($metadata['version'], $this->version, '=');
				if ($versionCorresponds) {
					$isDirty = FALSE;
				}
			}
		}
		return $isDirty;
	}

	/**
	 * Remove Cookbook directory
	 */
	public function remove() {

		if (is_dir($this->getCookbookDirectory())) {
			// remove directory that must be replaced.
			$command = 'rm -rf ' . $this->getCookbookDirectory();
			exec($command);
		}
	}

	/**
	 * Download Cookbook
	 */
	public function download() {
		$url = sprintf('http://community.opscode.com/cookbooks/%s/versions/%s/downloads',
			$this->name,
			str_replace('.', '_', $this->version)
		);

		$command = sprintf('cd cookbooks; curl -s -L -o %s %s',
			$this->getCookbookTarBall(),
			$url
		);

		// Downloading
		echo sprintf('Downloading "%s" version %s%s', $this->name, $this->version, chr(10));
		exec($command);

		try {
			$cookbookTarballAndPath = sprintf('cookbooks/%s', $this->getCookbookTarBall());
			$phar = new PharData($cookbookTarballAndPath);
			$phar->extractTo('cookbooks'); // extract all files

			// Clean up tarball
			unlink($cookbookTarballAndPath);
		} catch (Exception $e) {
			// handle errors
			var_dump($e);
		}

	}

	/**
	 * @return string
	 */
	protected function getCookbookDirectory() {
		return 'cookbooks/' . $this->name;
	}

	/**
	 * @return string
	 */
	protected function getMetadataFile() {
		return $this->getCookbookDirectory() . '/metadata.json';
	}

	/**
	 * @return string
	 */
	protected function getCookbookTarBall() {
		return sprintf('%s_%s.tar.gz', $this->name, $this->version);
	}
}