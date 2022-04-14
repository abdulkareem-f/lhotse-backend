# Lhotse Backend Task


### Installation (first time)

1. Open in cmd or terminal app and navigate to project folder
2. Run following commands

    ```
    composer install
    cp .env.example .env
    cp .env.example .env.testing
    ```

3. Set your database information in `.env` & `.env.testing`, like:
    ```
    DB_DATABASE=lhotse_backend | lhotse_backend_testing
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4. Run following commands

    ```
    php artisan key:generate
    php artisan migrate:fresh --seed
    ```

### Auth

- Email: `admin@admin.com`
- Password: `admin_password`


### APIs
You can import the file (`Lhotse.postman_collection.json`) to postman and test the APIs, 
but don't forget to change the variables [`baseUrl`, `token`] according to your information
