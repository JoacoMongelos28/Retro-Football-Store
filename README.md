# Retro Football Store

Retro Football Store project is a web application designed for retro football jersey enthusiasts. This platform allows users to explore and purchase a variety of classic football jerseys from different eras.<br>

Features:<br>
• Secure Payments: Integration with Mercado Pago to ensure safe and fast transactions.<br>
• Efficient Search System: Includes a search function that allows filtering products based on user preferences.<br>
• Functional Shopping Cart: Users can add or remove jerseys from their cart, with automatic total cost calculation.<br>
• Intuitive User Interface: The application offers a simple and attractive browsing experience, making product exploration easy.<br>

<br>

Technologies:<br>
• Backend: PHP<br>
• ORM: Eloquent<br>
• Framework: Laravel<br>
• Template Engine: Blade<br>
• Database: PhpMyAdmin<br>
• Libraries: JQuery | Swiper<br>
• Frontend: HTML5 | CSS3 | Javascript<br><br>

> [!NOTE]
> Prerequisites:<br>
>• XAMPP<br>
>• Composer<br>

<br>

> [!IMPORTANT]
> 1.	Clone Repository: git clone https://github.com/JoacoMongelos28/Retro-Football-Store.git
> 2.	Open XAMPP and configure the my.ini file of the MySQL service. Search for max_allowed_packet and replace it with this:<br><br>
    max_allowed_packet=16M<br><br>
> 3.    Start the Apache and MySQL services.
> 4.	Open PhpMyAdmin and create a new database named Retro_Football_Store.
> 5.	Open the project and run the following command in the terminal:<br><br>
    composer install<br><br>
> 6.	Create a .env file using the .env.example file as a template and configure the database with your details:<br><br>
    DB_CONNECTION=your_database  
    DB_HOST=your_hostname  
    DB_PORT=your_port  
    DB_DATABASE=Retro_Football_Store
    DB_USERNAME=your_username
    DB_PASSWORD=your_password<br><br>
> 7.	Map the tables in the database with the following command:<br><br>
    php artisan migrate --seed<br><br>
> 8.	Import the 'camiseta.sql' file into phpMyAdmin, which is located in the 'sql' folder within the project.
> 9.	Register as a user and enjoy the page.
> 10.	If you want to pay for the football shirts, you must log in and pay using the following Mercado Pago account<br><br>
> User: TESTUSER2053005099<br>
> Password: FC981613#be14#4f1f#<br>
> Verification code: 810057<br><br>
> 11.   If you want to log in as an administrator, these are the credentials:<br><br>
Username: admin<br> Password: admin<br>
> 12.   Enjoy the app!
