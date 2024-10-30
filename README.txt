=== Cool Correo Argentino - WooCommerce ===
Contributors: matiasanca
Tags: correoargentino, woocommerce, shipments
Requires at least: 5.4.1
Tested up to: 6.4.1
Requires PHP: 7.0
Stable tag: 1.3.21
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Método de Envío de WooCommerce para Correo Argentino.

== Description ==
Calculadora de Envíos para Correo Argentino
Envío a Sucursales y a Domicilio
Cálculo de costo Manual ( por tarifario ), o automático ( directo por API a Correo Argentino )
Opciones: Envío Gratuito | Recargo | Descuento | Costo Fijo
Generación de archivo CSV Lote para importar en Correo Argentino
Se incorpora el cálculo por Peso y Peso Volumétrico
Calculadora de Envío en página de Producto
Selector y buscador de sucursal
Particionamiento de órden en varios paquetes

== Installation ==
https://vimeo.com/883785627

== Instalación y Configuración ==
https://vimeo.com/883785627

== Funcionamiento ==
https://vimeo.com/883789516

== Exportación a Correo Argentino ==
https://vimeo.com/883791698

== API KEY ==
¿Dónde consigo mi API Key?
Debes ingresar a la siguiente dirección [https://coolcaweb.manca.com.ar](https://coolcaweb.manca.com.ar).

== Tarifario de precios ==
Correo Argentino ya no comparte el tarifario de precios públicamente, pero pueden comunicarse y solicitarlo.


== Changelog ==
= 1.0 =
First release

= 1.0.3 =
Add Shipping Meta Data "Branch Description"

= 1.0.4 =
Add Default Weight

= 1.0.5 =
Fix field Cod. Área Cellphone

= 1.1.0 =
Add Price by Volume

= 1.1.1 =
Fix error "divisionByZero"
Add error for product dimentions required

= 1.1.2 =
Add Product Cost Calculator

= 1.1.3 =
Fix error on Product Cost Calculator when ajax refresh cost, format wc_price

= 1.1.4 =
Add some int and float casts

= 1.3.0 =
New way to calc cost
New package handling
API integration to get Costs and Branchs
New export layout

= 1.3.1 =
Fix branches and some styling

= 1.3.2 =
Fixing branches Calc

= 1.3.3 = 
Fix bug getting quotations when dimentions is not an Integer

= 1.3.4 =
Fix bug in getting quotation from table method
Fix on paq splitting when table method is setted

= 1.3.5 =
Fix default length

= 1.3.6 =
Fix Product Calculator Nonce

= 1.3.7 =
Fix bug getting Product Paqars

= 1.3.8 =
Add Advanced/Master Settings

= 1.3.9 =
Fix max Volumetric

= 1.3.10 =
Fix Weight bug

= 1.3.11 =
Replace FILTER_SANITIZE_STRING (deprecated) -> FILTER_SANITIZE_FULL_SPECIAL_CHARS
WC_CoolCA dynamic properties declared bug
Fix nonce validation on Checkout for new versions of woo
Fix Branch Selector Style for mobile

= 1.3.12 =
Add "My products can be stacked" option

= 1.3.13 =
Add column to export file

= 1.3.14 =
Fix 1.3.13 when export, external id column

= 1.3.15 =
Add Product Stacked on production

= 1.3.16 =
Change URL to get Branches in Free Mode

= 1.3.17 =
Fix bug when saving Branch data

= 1.3.18 =
Feature Change how export process recognition branches
Enhacement Show confirmation when duplicate row in export table
Export css minor changes

= 1.3.19 =
Hotfix export branch recongnition

= 1.3.20 =
Feature Add Total Amount condition to aplly discounts/charges
Feature Add Metabox to Order

= 1.3.21 =
Fix bug on Order MetaBox
Add Express Services
Add Cache to Request API
Search branch by name
