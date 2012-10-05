name "vagrant"
description "Role for our virtual development environments. Adds more sensitive settings for improving development. These nodes have typically a more open security guideline to allow access for developers."

run_list(
)

override_attributes(
  "mysql" => {
    "server_root_password"      => "foobarbazbam",
    "tunable" => {
      "key_buffer"              => "32M",
      "innodb_buffer_pool_size" => "128M"
    }
  },
  "etherpadlite" => {
    "database" => {
      "password" => "foobarbazbam"
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

  "zabbix" => {
    "server" => {
      "dbpassword" => "zabbixxx"
    }
  },

  "gerrit" => {
  },
  "gitweb" => {
    "hostname" => "git.typo3-chef-repo.dev"
  }
)
