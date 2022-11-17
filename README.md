<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Foodic Coding Challenge
In this repository I perform the foodic coding challenge which is sent to me over the email. I use Laravel framework to perform the assignment. You can easy install the application by following the below steps:

### Step 1
`clone the repository`

### Step 2
Run `composer install`

### Step 3
Copy .env.example and rename it to .env
Run `cp .env.example .env`

### Step 4
Update environment variable value in .env
`MAIL_USERNAME="YOUR_USERNAME"`
`MAIL_PASSWORD="YOUR_PASSWORD"`
`ADMIN_EMAIL="YOUR EMAIL"`

### Step 5
Run `php artisan migrate`

### Step 6
Run `php artisan db:seed`
This will create following records in database:
    - Units: Kilogram and Gram
    - Ingredients: Beef 20kg, Cheese 5kg, Onion 1kg
    - Product: Burger with ingredients(beef-150g, cheese-30g and onion-20g)

### Step 7
As Queue Job is used with default database drive please run following command to send the email notification:
Run `php artisan queue:work`

### NOTE
#### How it works:
 - I create a route '/order'. This route accept the payload which contain product_id, and quantity.
 - First I check do we have enough stock of ingredients to complete the order.
 - If we have enough stock of ingredients then we add the order to database and maintain the ingredients stock
 - If any of the ingredient stock is below than 50% then email has been sent to admin with the help of queue.

#### Some logical explanation:
 - I use the smallest unit of weight(gram) for ingredient stock management
 - I add two addition columns (in_stock_quantity and is_stock_notification_sent) in the ingredient table, is_stock_notification_sent is for tracking is the email has been sent to admin against the specific 
ingredient or not. And in_stock_quantity is for checking when 50% will be reach. (If we want to update the ingredient or add new ingredient then is_stock_notification_sent will set to 0 again).

## Points of improvements:
 - We can use Service class to make the controller much simpler.
 - We can use Redis for track is admin has been notified against low quantity of the specific ingredient
 - We can also improve the response send from the controller.
