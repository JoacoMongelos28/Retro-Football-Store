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
    max_allowed_packet=16M
<br><br>
> 3.    Start the Apache and MySQL services.
> 4.	Open PhpMyAdmin and create a new database named retro_football_store.
> 5.	Open the project in your code editor and run the following command in the terminal:<br><br>
    composer install
<br><br>
> 6.	Create a .env file using the .env.example file as a template and configure the database with your details:<br><br>
    DB_CONNECTION=mysql (Change according to your database)    
    DB_HOST=your_hostname    
    DB_PORT=your_port    
    DB_DATABASE=retro_football_store    
    DB_USERNAME=your_username    
    DB_PASSWORD=your_password
<br><br>
> 7.    In the same file (.env), paste this code.<br><br>
    MERCADOPAGO_PUBLIC_KEY=APP_USR-705806417372706-011002-1fe4d4af65b4f79f238b03a2c2755cf6-2201985025
    MERCADOPAGO_ACCESS_TOKEN=TEST-2591301662628250-112013-bef180cd58f4d64d50d056542d3f0d85-151386142
<br><br>
> 8.    If you want to receive an email, modify the .env file with the following code using your MailTrap.io credentials.<br><br>
    MAIL_MAILER=log    
    MAIL_SCHEME=null    
    MAIL_HOST=127.0.0.1    
    MAIL_PORT=2525    
    MAIL_USERNAME=null    
    MAIL_PASSWORD=null    
    MAIL_FROM_ADDRESS="hello@example.com"    
    MAIL_FROM_NAME="Retro Football Store"
<br><br>
> 9.    Generate a key for the application with this command:<br><br>
    php artisan key:generate
<br><br>
> 10.	Map the tables in the database with the following command:<br><br>
    php artisan migrate --seed
<br><br>
> 11.	Import the 'camiseta.sql' file into the 'camiseta' table in phpMyAdmin, which is located in the 'sql' folder within the project.
> 12.	Register as a user and enjoy the page.
> 13.	If you want to pay for the football shirts, you must log in and pay using the following Mercado Pago account<br><br>
    User: TESTUSER2053005099<br>
    Password: FC981613#be14#4f1f#<br>
    Verification code: 810057<br><br>
> 14.   If you want to log in as an administrator, these are the credentials:<br><br>
    Username: admin<br>
    Password: admin<br><br>
> 15.   Enjoy the app!
