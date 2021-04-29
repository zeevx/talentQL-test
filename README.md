<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>



## TalentQL Test

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.


### Codebase Explaination

- When a user is being registered, he is assigned a role - either photographer/product-owner.
- A product-owner can CRUD products.
- A photographer can CRUD a photograph for a product.
- All validation are properly highlighted
- Authentication was done with Passport
_ Wrote custom middleware and classes
- Neat and starightforward codebase
- Check API @ [https://documenter.getpostman.com/view/11560803/TzK16aZA](https://documenter.getpostman.com/view/11560803/TzK16aZA)


### How to Install

- clone repository and cd into it
- run composer install
- run cp .env.example .env
- configure your DB correctly in the the .env file
- run php artisan key:generate
- php artisan migrate
- php artisan db:seed
- run php artisan passport:install
- run php artisan serve - url = http://127.0.0.1:8080
- To test run composer test


### Documentation for API endpoints

[https://documenter.getpostman.com/view/11560803/TzK16aZA](https://documenter.getpostman.com/view/11560803/TzK16aZA)

