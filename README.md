## Leisurelab sport 

The goal of the application is to show an overview of the total headcount recorded in the
last year, by the various sports and activities the leisure provider has recorded.

### Development team contact
- jiang@leisurelabs.co.uk

### Development References
- [Laravel Reference](https://laravel.com/docs)
- [Wiki](https://leisurelabs.atlassian.net/wiki/spaces/MET/)
- [Jira](https://leisurelabs.atlassian.net/jira/software/projects/MET/)
- API Specifications (@jiang)
- Code Review Process

### Local Installation and Deployment
#### Windows
1. Install php 8.1+ and install / update the composer
2. Create a new database on MySQL server
3. Enter project, update the .env file (if it is .env.example then rename it .env file)
4. Generate the key `php artisan key:generate`
5. Run the commands `composer dumpautoload` (dump-autoload command which won't download anything new, but looks for all of the classes it needs to include again and helps to regenerates the list of all classes that need to be included in the project).
6. Clear the config cache `php artisan config:cache`
7. Run `php artisan migrate --seed` to migrate db files
8. Run `php artisan import-demo-data` to import demo data
9. Run `php artisan serve` to Start the server

### Test server
#### Running on Nginx server on Linux, follow these steps:
1. Install the required software: Make sure Nginx, PHP, and Composer are installed on the server. You can use package managers like apt or yum to install these software packages.
2. The same step as above 2-7
3. Configure Nginx: Create a new Nginx configuration file and point it to the public directory of your Laravel project. The configuration file is typically located in the /etc/nginx/sites-available/ directory.
4. Restart Nginx: Once the configuration is completed, restart the Nginx service to apply the changes.

### Code
1. Get server source code `git clone git@github.com:lijiang/leisuresport.git`

### Commands
1. `php artisan sport:archive_head_count_last_year` archive sport head count for last year


### Properties
```php
# Debug mode
APP_DEBUG=true

# Database
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
