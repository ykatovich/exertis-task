## About Task

A small Laravel application, to consume and display free form CSV file  containing text, numbers and dates on a scheduled basis.


## Requirements

- *Loaded file should be stored in MySQL database*
- *On each successful load, a CSV file should be renamed or moved to another location, so it’s not picked up twice.*
- *On each successful load, previous data load shall be removed from MySQL database*
- *A load execution log should be kept in MySQL database, and available for inspection in the back-end of the application*
  - *A log should present: location of the CSV file, load status ( success, fail ) and amount of records added*
- *A manual load option should possible*




#### Back-end configuration

- *File location should be defined via settings in the admin area of the app*
- *File name ( or pattern ) should be defined via settings in the admin area of the app*
- *Load schedule should be defined / editable via settings in the admin area of the app*
- *It should be possible to enable / disable load schedule*

#### Security

- *The site should require logon before accessing any pages.*


## How to start

1. ```composer install```
2. ```Configure your .env```
3. ```php artisan migrate --seed```
4. `` php -S localhost:8000``

## How to start scheduler

- Before starting the scheduler, create folders that we assume already exist. (and in import map we have csv files)

```storage/app/public/import```

```storage/app/public/failed_import```

- There are three files for testing in the project
    - ``oscar_age_male1.csv``
    - ``oscar_age_male2.csv``
    - ``oWrongData.csv``

And start scheduler

1. ```php artisan schedule:work```

