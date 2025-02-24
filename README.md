[![Deploy to FTP](https://github.com/aditlfp/sppd/actions/workflows/main.yml/badge.svg)](https://github.com/aditlfp/sppd/actions/workflows/main.yml)

<p align="center"> 
  <a href="https://skillicons.dev">
    <img src="https://skillicons.dev/icons?i=laravel,jquery,js,vite" />
  </a>
</p>

This web application was created to answer the difficulty of recording and knowing the official travel order budget and to make it easier to manage and record official travel order data so that data that has been recorded no longer requires a hard file

**Feature :**

1. Admin Dashboard For control a sppd system
2. Direktur, Direksi, Kabag, adum Control system
3. Accept, Reject, for sppd
4. Role Based System to guard some menu
5. Multi Database System

**How To Work :**

1. First Download or clone this project
2. Run `composer update` and `composer install` in terminal on root this project 
3. After composer done, Run `npm install` in terminal on root this project
4. Set `.env` you can copy .env.example and rename to .env or write this command `cp .env.example .env`
5. Set your database `DB_*` and `DB2_*` on `.env`
6. After you set `.env` run `php artisan migrate --seed`
7. If Successfull run `php artisan optimize`
8. After that run `php artisan serve` and in other command promt run `npm run dev`
9. When your web app error including `/ or Get` run `php artisan route:clear`


**Full Changelog**: https://github.com/aditlfp/sppd/compare/Main-Packages...SPPD_Packages
