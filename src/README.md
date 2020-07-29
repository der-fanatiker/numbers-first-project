# Numbers
This repository contains my first project - a system that allows users to get numbers for electronic documents for one 
or several companies.   

I made it in 2006 and this system allows: 
* allows user to login in - every company has a different, standalone database; 
* get a number for internal electronic documents system; 
* allows an administrator to login and control numbers for users.

I like this project a lot, and I want to rebuild it in several ways:
* using new technologies for practice;
* using new approaches for practise.

I'm a little ashamed for quality of this system but you should remember - it's my first system, that I'd build. 

## How-to run this project
You should run it with docker - my favorite tool for building services.
* Clone this repository
* Change directory to **docker-numbers**
* Run **docker-compose build** 
* Run **docker-compose up** or **docker-compose up -d**
* Open [http://localhost/](http://localhost/) in your browser.  