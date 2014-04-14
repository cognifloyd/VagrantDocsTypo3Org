<?php

/**
 * Manage Cookbook with Git origin
 */
class CookbookGit {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var string
	 */
	protected $revision;

	/**
	 * @param string $name
	 * @param string $url
	 * @param string $revision
	 */
	public function __construct($name, $url, $revision) {
		$this->name = $name;
		$this->url = $url;
		$this->revision = $revision;
	}

	/**
	 * Sync Cookbook
	 */
	public function sync() {
		if(!is_dir($this->getCookbookDirectory())) {
			echo sprintf('Cloning "%s"%s', $this->name, chr(10));
			$command = sprintf('cd cookbooks; git clone -q %s', $this->url);
			exec($command);

			// Position cursor on good revision just after pulling
			if ($this->getCurrentRevision() !== $this->revision) {
				$command = sprintf('cd cookbooks/%s; git checkout %s', $this->name, $this->revision);
				exec($command);
			}
		} else {
			if ($this->getCurrentRevision() !== $this->revision && $this->doSync()) {
				$command = sprintf('cd cookbooks/%s; git fetch', $this->name);
				exec($command);
				$command = sprintf('cd cookbooks/%s; git checkout %s', $this->name, $this->revision);
				exec($command);
			}
		}
	}

	/**
	 * @return string
	 */
	protected function getCurrentRevision() {
		$command = sprintf('cd cookbooks/%s; git rev-parse HEAD', $this->name);
		exec($command, $output);
		return empty($output[0]) ? '' : $output[0];
	}

	/**
	 * @return string
	 */
	protected function getCookbookDirectory() {
		return 'cookbooks/' . $this->name;
	}

	/**
	 * @return bool
	 */
	public function doSync() {
		$settings = parse_ini_file(SETTING_FILE, true);
		return (bool)$settings['global']['syncGit'];
	}
}