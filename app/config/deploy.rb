# Capifony documentation: http://capifony.org
# Capistrano documentation: https://github.com/capistrano/capistrano/wiki

logger.level = Logger::MAX_LEVEL

set :application, ""
set :domain,      ""
set :deploy_to,   ""
set :user,        ""

role :web,        domain
role :app,        domain, :primary => true
role :db,         domain

set :scm,         :git
set :repository,  ""
set :branch,      ""
set :deploy_via,  :remote_cache

ssh_options[:forward_agent] = true

set :use_composer,   true
set :update_vendors, false
set :dump_assetic_assets, true

set :writable_dirs,     ["app/cache", "app/logs"]
set :webserver_user,    "www-data"
set :permission_method, :acl
set :use_set_permissions, false
set :shared_files,    [app_path + "/config/parameters.yml", web_path + "/.htaccess", web_path + "/robots.txt"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor"]

set :model_manager, "doctrine"
set :symfony_env_prod, "prod"

set :use_sudo,    false

set :keep_releases, 3
after "deploy", "deploy:cleanup"

# For hipchat users
require 'hipchat/capistrano'
set :hipchat_token, ""
set :hipchat_room_name, ""
set :hipchat_announce, false # notify users
set :hipchat_color, 'purple' # finished deployment message color
set :hipchat_failed_color, 'red' # cancelled deployment message color
