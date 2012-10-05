name "debian"
description "Role applied to all Debian systems."

run_list(
  "recipe[apt]",
  "role[base]"
)

override_attributes(
  "chef_client" => {
    "init_style" => "init"
  }
)
