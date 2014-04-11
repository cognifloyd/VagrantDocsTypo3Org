name "vagrant"
description "Role for our virtual development environments. Adds more sensitive settings for improving development. These nodes have typically a more open security guideline to allow access for developers."

run_list(
)

override_attributes(
  "mysql" => {
    "server_root_password"      => "root",
    "tunable" => {
      "key_buffer"              => "32M",
      "innodb_buffer_pool_size" => "128M"
    }
  },
  "apache" => {
    "prefork" => {
      "startservers"    => 2,
      "minspareservers" => 2,
      "maxspareservers" => 5
    }
  },
  "postfix" => {
    "aliases" => {
      # TODO ct 2012-05-13 Redirect all outgoing mails to vagrant user
      #"*"    => "vagrant"
    }
  },
)
