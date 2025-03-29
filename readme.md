# Test case take away menu admin

View, create, edit and delete products in a back-office view.

## Installation instructions

1. Clone or download and unzip the repo

2. Install and configure a MySQL server. Could be this one: https://dev.mysql.com/downloads/installer/

3. Setup .env with database connection. Start with .env.example which has most of it ready to go

4. Install PHP if you have not already. Then install composer: https://getcomposer.org/download/

5. Run `composer global require laravel/installer`

6. Run `composer install` and `composer update` if needed

7. Run `npm install` and `npm run build`

8. Run `php artisan migrate` and create the database

9. Run `composer run dev` to start the app

10. Open http://localhost:8000/ and create an account, then you can start to create products
