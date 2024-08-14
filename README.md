Steps to run the project
1. Clone the project by the help of : git clone https://github.com/360-dev-suraj/student-tracker.git
2. Navigate to project in termminal and run : composer install (Note composer must be install)
3. Create .env file in root directory and update the like below with your configuration
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=student
    DB_USERNAME=root
    DB_PASSWORD=

4. Navigate to project using terminal and run : php artisan key:generate and after that php artisan optimize:clear
5. Then run php artisan migrate (this will create all the required tables in your database)
6. After that run npm install and npm run dev (Note nodejs and npm must be install)
7. After all steps run php artisan serve and hit below url to create new user to logged in
   http://127.0.0.1:8001/register (This will register the user)
   http://127.0.0.1:8001/login (Here you can log-in)
