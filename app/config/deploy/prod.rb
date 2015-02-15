set :domain,      ""
set :deploy_to,   "/var/www/php/#{application}"
set :user,        "deployer"

set :branch,      "master"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain