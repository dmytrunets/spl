set :domain,      "188.226.163.161"
set :deploy_to,   "/var/www/php/#{application}.evercodelab.com"
set :user,        "deployer"

set :branch,      "master"

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain