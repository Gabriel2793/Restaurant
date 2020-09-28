# Quetzalcóatl
![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/quetzalcoatl.jpg)


![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/laravel.png)

Este sistema se realizó con la finalidad de dar un ejemplo de mis habilidades con varios frameworks y no se realizó con fines de lucro, este sistema sirve para mostrar los platillos que se sirven en este establecimiento ficticio, además de ser una ayuda a los camareros para tomar la orden, el diseño del sistema ayuda a que se pueda utilizar en varios dispositivos, incluyendo  los celulares, las siguientes mensiones de software es lo que se utilizó para su desarrollo.

El sistema se realizó con laravel 7, éste requiere de la instalación del siguiente software:

1. PHP 7
2. BCMath PHP Extension
3. Ctype PHP Extension
4. Fileinfo PHP extension
5. JSON PHP Extension
6. Mbstring PHP Extension
7. OpenSSL PHP Extension
8. PDO PHP Extension
9. Tokenizer PHP Extension
10. XML PHP Extension
11. Apache 2, debe activarse el protocolo https
12. composer

Para el almacenamiento de los datos se eligio el siguiente RDBMS:

1. MySQL 8

También se agrego bootstrap 4 para la maquetación del sitio web, al igual que ayudo a crearlo de manera responsiva, se utiliza JQuery para manipular los elementos en el sitio web al igual que para solicitar datos al servidor.

El sistema usa Mapbox para mostrar un mapa, agregandole a éste funcionalidad con Leaflet para mostrar la ubicación actual del usuario, además se logra obtener la ubicación de la sucursal más cercana a su localización.

Por último se utiliza el plugin [Image map resizer](https://github.com/davidjbradshaw/image-map-resizer) para hacer responsive varios links de una imagen.

Fue probado en Ubuntu 18.04

![](https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcRJid49quIoaZ888UjzQhM0QbNHriODlwGb1Q&usqp=CAU)

Este proyecto es un sistema para un restaurante, en el cual la página principal se muestran los platillos que se ofrecen, cómo en la siguiente imagen:

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Home.jpg)

El cliente podra visualizar además de los platillos las sucursales existentes en el mundo, por lo que al dar click en "Sucursales", se mostrara un Mapa de Mapbox, que se le agrego funcionalidad con el framework de leaflet, por lo que el cliente podrá dar click en alguno de los botones para visualizar en el mapa la sucursal o su localización actual y también podra solicitar la sucursal más cercana a su localización, la siguiente imagen es una muestra de lo que vera:

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/BranchOffices.png)

Al mostrarle al cliente su mesa, se le tomara la orden por lo que el mesero iniciara sesión dando click en "Ingresar"  y facilitando su usuaro y contraseña, posteriormente se visualizará una página con una imagen haciendo referencia al acomodado de las mesas en el establecimiento, cómo se muestra en la siguiente imagen:



![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Laravel2.png)

Al dar click en la mesa asignada al cliente, se mostraran dos columnas extras, una para los platillos a seleccionar, también se enseñan varios botones con números, al dar click en uno de ellos se recuperarán los platillos respectivos, el primero de ellos, regresara los primeros nueve, el segundo los siguientes nueve, así susecivamente hasta llegar al último botón que mostrara los últimos platillos, y la segunda columna sirve para mostrar los platillos seleccionados que quiere el cliente en su orden, para guardar la orden se dara click en el botón "Enviar":

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Laravel4.png)

La siguiente imagen muestra cómo se ve la lista de platillos seleccionados, en caso de querer eliminar algún platillo de la orden, se debe dar click en el botón rojo con una x correspondiente al elemento:

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Laravel.jpg)

Siempre se mostraran nueve platillos a la vez, por lo que al dar click en algún número en la parte inferior, se solicitaran los siguiente nueve platillos o los restantes, la siguiente imagen lo ejemplifica:

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Laravel5.png)

NOTA: Se utiliza JQUERY para quitar de la vista los platillos que se muestran y se reemplazan por los nueve platillos que siguen o en su defecto los que quedan. La paginación se hace de manera dinamica, por lo que al tener veinte platillos en la base de datos se mostraran tres números para ir por toda la lista, pero se tiene el campo de busqueda para encontrar rapidamente lo que se desea. Al dar click en el botón enviar, se almacenara la orden, pero si se sale de la sesión o se actualiza la página antes de ésta acción estos desaparecerán.

Al enviar los platillos se podran agregar más o eliminar los deseados, para almacenar los cambios se debe dar click en el botón Äctualizar", en caso contrario de que el cliente decee cancelar, se tiene un botón de color rojo con el texto "cancelar" y por último cuando el cliente termine de consumir la comida y pague se tiene el botón con el texto "el cliente pago" en color azul, la siguiente imagen ilustra los botones:



![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Laravel3.jpg)

También se tiene la parte de administración del sistema, para agregar más platillos o más sucursales, las siguientes dos imagenes muestran los formularios para agregarlos:

![]()![Screenshot_2020-09-16 Add Restaurant](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/AddRestaurant.png)

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/AddRestaurant(1).png)

El registro del empleado es esencial, para así cuando ingrese al sistema a tomar la orden de un cliente se sepa quien fue:

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Laravel6.png)

Este sistema está optimizado para poder ser utilizado con dispositivos moviles, cada una de las siguientes imágenes muestra la presentación de cada página en el sitio web de manera responsiva:

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Screenshot_20200916_211638_com.android.chrome.jpg)

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Screenshot_20200916_211648_com.android.chrome.jpg)

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/juarez.jpg)

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Screenshot_20200916_211735_com.android.chrome.jpg)

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Screenshot_20200916_211746_com.android.chrome.jpg)

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Screenshot_20200916_211753_com.android.chrome.jpg)

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/Screenshot_20200926_184639_com.android.chrome.jpg)

La siguiente imagen muestra el diagrama entidad relación de la base de datos:

![](https://raw.githubusercontent.com/Gabriel2793/Restaurant/master/readmeimages/ER.jpg)



