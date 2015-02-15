set :stage_dir, 'app/config/deploy'
require 'capistrano/ext/multistage'

set :stages, %w(prod staging)
set :default_stage, "staging"

logger.level = Logger::MAX_LEVEL

set :application, ""

set :scm,         :git
set :repository,  "git@github.com:EvercodeLab/#{application}.git"
set :branch,      "master"
set :deploy_via,  :remote_cache

ssh_options[:forward_agent] = true
default_run_options[:pty] = true

set :use_composer,   true
set :update_vendors, false
set :dump_assetic_assets, true

set :writable_dirs,   [app_path + "/cache", app_path + "/logs", app_path + "/sessions"]
set :webserver_user,    "www-data"
set :use_set_permissions, false
set :shared_files,    [app_path + "/config/parameters.yml"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", "vendor", app_path + "/sessions"]
set :composer_options,  "--no-dev --verbose --prefer-dist --optimize-autoloader --no-progress"

before "deploy:restart", "deploy:migrate"
set :model_manager, "doctrine"
set :interactive_mode, false
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

require "whenever/capistrano"
set :whenever_command, "whenever --load-file app/config/schedule.rb --set environment=#{symfony_env_prod}"

namespace :symfony do
  desc "Clear apc cache"
  task :clear_apc do
    capifony_pretty_print "--> Clear apc cache"
    run "#{try_sudo} sh -c 'cd #{current_path} && #{php_bin} #{symfony_console} apc:clear --env=#{symfony_env_prod}'"
    capifony_puts_ok
  end
end

after "deploy", "symfony:clear_apc"
after "deploy:rollback:cleanup", "symfony:clear_apc"