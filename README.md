# HOW TO
## Run Locally  

Go to the project directory  
~~~bash  
  cd my-project
~~~

Change configs
~~~bash  
cp .env.example .env
~~~

Create the symbolic link for Storage
~~~bash  
php artisan storage:link
~~~ 

Install  dependencies
~~~bash  
composer install
or composer install --ignore-platform-reqs
~~~ 

Generate key
~~~bash  
php artisan key:generate
~~~ 

Migrate & Seed
~~~bash  
php artisan migrate --seed
~~~ 
Admin email: admin@owlweb.com.ua
password: randomized, is shown in the console after ```Seeding: Database\Seeders\AdminSeeder```


## Demo content(only on fresh db)
1. Copy folder ```public/demo``` to ```public/storage/media/demo```
~~~bash
cp -r ./public/demo ./storage/app/public/media/demo
~~~
2. Seed DB
~~~bash
php artisan db:seed --class=DemoSeeder
~~~

## Docker(Sail)
1. Install Docker and WSL2(Ubuntu 20.04).
Useful links:
- https://docs.docker.com/desktop/windows/wsl/
- https://support.atlassian.com/bitbucket-cloud/docs/set-up-an-ssh-key/
Copy keys from Windows
~~~bash
cp -r /mnt/c/Users/<username>/.ssh ~/
~~~
Run ssh agent
~~~bash
eval $(ssh-agent)
~~~
Change permissions
~~~bash
chmod 600 ~/.ssh/<private_key_file>
~~~
Add key
~~~bash
ssh-add ~/.ssh/<private_key_file>
~~~
Check
~~~bash
ssh -T git@bitbucket.org
~~~

2. Clone project in WSL.

3. Make temporary alias: ```alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'``` or create permanent
~~~bash
cd ~
touch .bash_aliases
nano .bash_aliases
~~~
Add ```alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'```

4. Make config from example(.env.example) ```cp .env.example .env```

5. Install dependencies ```docker run --rm -u "$(id -u):$(id -g)" -v $(pwd):/var/www/html -w /var/www/html laravelsail/php80-composer:latest composer install --ignore-platform-reqs```

6. Run Sail ```sail up``` or ```sail up -d```. Stop - ```sail stop```. Bug fix when stuck on ```gpg: keybox '/root/.gnupg/pubring.kbx' created```:
In your project folder go to /vendor/laravel/sail/runtimes/8.0/Dockerfile. On line:21 remove :80 so the line should be this: ```&& echo "keyserver hkp://keyserver.ubuntu.com" >> ~/.gnupg/dirmngr.conf \```

7. Rest of commands from Run Localy(replacing ```php``` with ```sail``` alias or use ```./vendor/bin/sail``` )

8. Run VSCode ```code .```

## DB access
~~~bash
docker-compose exec mysql bash
mysql -u root -p
password: password
GRANT ALL PRIVILEGES ON *.* TO 'sail'@'%';
FLUSH PRIVILEGES;
EXIT;
exit
~~~

## Files permissions
assuming www-data is your web server user, you can run:
~~~bash
sudo chown -R www-data:www-data /path/to/your/laravel-directory
~~~
And this is good, because your webserver will own files and can execute. The bad part is that your logged in user (either via FTP or SSH) will probably be a different user so what you want to do is to add this user to the webserver group:
~~~bash
sudo usermod -a -G www-data ubuntu
~~~
Of course, this assumes your webserver is running as www-data (the Homestead default), and your user is ubuntu (it’s vagrant if you are using Homestead).

Then, a good practice is to set all your directories to 755 and all of your files to 644… SET file permissions using the following command:
~~~bash
sudo find /path/to/your/laravel-directory -type f -exec chmod 644 {} \;
~~~
SET directory permissions:
~~~bash
sudo find /path/to/your/laravel-directory -type d -exec chmod 755 {} \;
~~~
Your user as owner
What I prefer is to own all the directories and files I am working with (it makes working with everything much easier), so I do:
~~~bash
sudo chown -R my-user:www-data /path/to/your/laravel-directory
~~~
Then I can just give these permissions to myself and the webserver user:
~~~bash
sudo find /path/to/your/laravel-directory -type f -exec chmod 664 {} \;
sudo find /path/to/your/laravel-directory -type d -exec chmod 775 {} \;
~~~
One thing you don’t want to forget is to give the webserver access to read and write files in the cache folder

Your webserver will need to upload and store data as well so make sure you give the permissions for the storage folder as well:
~~~bash
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
~~~

## Git Patches
~~~bash
git format-patch <commit sha>^..<commit sha> --stdout > <patch-name>.patch
git am --3way <patch-name>.patch   
~~~

## Telescope After Installation
Copy telescope migration from vendor to database/migrations/telescope subfolder
~~~bash
php artisan migrate --path=database/migrations/telescope
~~~

Remove App\Providers\TelescopeServiceProvider::class from config/app.php because all providers inside config/app.php is automatically loaded. But in your production environment, laravel/telescope isn't installed that means Laravel\Telescope\TelescopeApplicationServiceProvider is undefined and App\Providers\TelescopeServiceProvider can not extend an undefined class.

Register App\Providers\TelescopeServiceProvider::class manually inside app/Providers/AppServiceProviders.php
~~~bash
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (class_exists(TelescopeApplicationServiceProvider::class)) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
~~~
