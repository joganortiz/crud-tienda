## Pasos para que pueda funcionar el desarrollo

al clonar el reposotrio debe instarle el composer
- comando "composer install"
- despues en el archivo .env debera de colocarle una APP_KEY, su valor puede ser este base64:d7Zoaaw07swcsClEiGbzkWcmyajx7rGOmjG63K3+Jek=
- Nombre de la base de datos debe ser "crud-tienda"
- al realizar estos paso ya queda solo correr el proyecto con el siguiente comando, "php artisan serve"


## Sentencias sql
- El producto que más stock tiene: "SELECT *  FROM `products` ORDER BY stock DESC, name ASC LIMIT 1"
- El producto más vendido: "SELECT SUM(amount) as vendidos, idProduct, nameProduct FROM `sales_products` GROUP BY idProduct ORDER BY vendidos DESC LIMIT 1" 

## Link prueba desarrollo
http://crudtenda.herokuapp.com/public/
