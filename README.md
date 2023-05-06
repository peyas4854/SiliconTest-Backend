## Installation

### Clone the repository
    https://github.com/peyas4854/SiliconTest-Backend

### Switch to the repo folder 

     cd repo_folder_name 

### Install all the dependencies using composer

    composer install

### Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

### Database configuration

    DB_DATABASE=your_database_name
    DB_USERNAME=your_user_name
    DB_PASSWORD=your_password

### Generate a new application key & storage link create

    php artisan key:generate
    php artisan storage:link

### Create table & dummy data from seeder

    php artisan migrate --seed

### Start the local development server

    php artisan serve

### Testing Credentials

    Admin Panel 
    ======
    Email: admin@gmail.com 
    password - 1234678
    User Panel 
    ========
    Email: user@gmail.com
    password - 1234678
