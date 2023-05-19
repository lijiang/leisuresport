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
1. install php 8.1+ and install / update the composer
2. enter project, update the .env file (if it is .env.example then rename it .env file)
3. Generate the key `php artisan key:generate`
4. Run the commands `composer dumpautoload` (dump-autoload command which won't download anything new, but looks for all of the classes it needs to include again and helps to regeneratesthe list of all classes that need to be included in the project).
5. clear the config cache `php artisan config:cache`
6. uncomment php extension in php.ini: pmg, fileinfo, pdo_mysql
7. Run `php artisan migrate --seed` to migrate db files
8. Run `php artisan nft:import-meta-data` to import initial nfts
9. Run `php artisan serve` to Start the server


### Code
1. Get server source code `git clone https://github.com/MetaGym-Inc/meta-gym-backend.git`

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
