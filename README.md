
# **checkout total Calculator**
## **introduction**
A simple shopping cart total price calculator with support for special offers.
- [**checkout total Calculator**](#checkout-total-calculator)
	- [**introduction**](#introduction)
	- [**Requirements**](#requirements)
	- [**Installation**](#installation)
		- [**Docker**](#docker)
			- [**Mac & Linux**](#mac--linux)
			- [**Windows**](#windows)
		- [**Without Docker**](#without-docker)
	- [**Getting started**](#getting-started)
		- [**This is Checkout API Endpoint**](#this-is-checkout-api-endpoint)
			- [Request](#request)
			- [Response](#response)
	- [**Test**](#test)
	- [**Notes**](#notes)
## **Requirements**
* PHP 8+
* composer 2+
* Docker 

## **Installation** 

### **Docker**
#### **Mac & Linux**
There is a bash script called init in the project root
which helps you to initialize the project and run it in port 8000 by docker.
<br>
**` * You are required to have docker installed on your system and configured to work without root permission.`**
<Br >
To run the script run the following commands.
```bash
./init
```
#### **Windows**
If you have git bash or any terminal which could interpret bash scripts you can run the init script.
```bash
./init
```
In case you don't have access to the bash terminal run the following commands.
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/opt \
    -w /opt \
    laravelsail/php80-composer:latest \
    composer install --ignore-platform-reqs
```
```bash
./vendor/bin/sail up -d
```
**`The above commands are going to start a test server on port 8000.`**
### **Without Docker**
**`You should have composer and PHP installed on your system.`**
```bash
composer install
```
```bash
php artisan serve
```
## **Getting started**
You can calculate the total price of cart items with the following API. 
### **This is Checkout API Endpoint**
```http
POST http://localhost:8000/api/checkout
```
#### Request
| Parameter    | Type  | Description                           |
| :----------- | :---- | :------------------------------------ |
| `products`   | Array | **Required**, Array of products       |
| `orderItems` | Array | **Required**, Array of cart items     |
| `rules`      | Array | **Required**, array of discount rules |

```json
{
   "products":[
      {
         "name":"A",
         "price":50
      },
      {
         "name":"B",
         "price":30
      },
      {
         "name":"C",
         "price":20
      }
   ],
   "orderItems":[
      {
         "product":"A"
      },
      {
         "product":"B"
      }
   ],
   "rules":[
      {
         "product":"A",
         "quantities":3,
         "special_price":130
      }
   ]
}
```

#### Response
```json
{
  "data": {
    "total": 80,
    "items": "A,B"
  }
}
```
## **Test**
If you started the project with the docker mode run the following command.
```bash
./vendor/bin/sail test --testsuit=Feature
```
otherwise run the foloowing command.
```bash
php artisan test --testsuit=Feature
```

## **Notes**
* If one of the ordered items or products or rule items does
  not have the required attributes it will be omitted from the request
  due to the `fault tolerance policy`.
* if one of the ordered products does not have the corresponding item in the products list it will be omitted from the list of the ordered items due to the `fault tolerance policy`.
