<?php

/**
 * Bootstrap environment.
 */
class CookbookManager {

	/**
	 * Synchronize Opscode Cookbooks.
	 *
	 * @return void
	 */
	public function syncOpscodeCookbooks() {
		$settings = $this->getSettings();

		foreach ($settings['community.opscode.com'] as $cookbook => $version) {

			$cookbook = new CookbookOpscode($cookbook, $version);
			if ($cookbook->isDirty()) {
				$cookbook->remove();
				$cookbook->download();
			}
		}
	}

	/**
	 * Synchronize Git Cookbooks.
	 *
	 * @return void
	 */
	public function syncGitCookbooks() {
		$settings = $this->getSettings();

		foreach ($settings['git'] as $cookbook => $repository) {
			$cookbook = new CookbookGit($cookbook, $repository['url'], $repository['revision']);
			$cookbook->sync();
		}
	}

	/**
	 * @return array
	 */
	protected function getSettings() {
		$settings = parse_ini_file(SETTING_FILE, true);
		return $settings;
	}
}