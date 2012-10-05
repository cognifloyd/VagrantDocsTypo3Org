name "base"
description "base role applied to all nodes."

run_list(
  # TODO ct 2012-05-12 Refactor packages into cookbooks
  "recipe[chef_handler]",
  "recipe[base]",
  "recipe[locales]",
  "recipe[openssh]",
  "recipe[git]",
  "recipe[rsync]",
  "recipe[screen]",
  "role[postfix]"
)

default_attributes(
  "chef_client" => {
    "server_url" => "https://chef-api.typo3.org"
  },
  "chef_handler" => {
    "handler_path" => "/var/chef/handlers"
  }
)
