name "site-docs"
description "Server hosting the docs websites"

run_list(
  "recipe[beanstalkd]",
  "recipe[chef-solo-search]",
  "recipe[site-docstypo3org]"
)

# Attributes applied no matter what the node has set already.
override_attributes(
  'beanstalkd' => {
    'start_during_boot' => 'yes'
  },
  'mysql' => {
    'bind_address' => 'localhost'
  },
  'users' => []
)
