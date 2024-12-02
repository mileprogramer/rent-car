## Backend REST API Project

This is the backend REST API project for the [rentcar frontend application](https://github.com/mileprogramer/rentcar).  
For the business scenario, please refer to the frontend repository linked above.
Live app is at
https://rentcardip.mileprogramer.rs

### To Start the Project
1. Run `composer install` to install dependencies.
2. Run `php artisan migrate --seed` to set up the database and seed initial data.
3. Run `php artisan key:generate` to generate the application key.
4. Run `php artisan storage:link` to create symbolic links for file storage.

### App Structure
This project follows the standard Laravel structure. However, for handling requests and responses, I have structured the business logic as follows to keep the controllers clean:

1. **Controller**  
   The controller is responsible for accepting the request calling the request instace of class and operate with data.

2. **Repository**  
   The repository contains complex SQL queries and logic that interact with the database.

This structure separates concerns, making the application easier to maintain and extend.
