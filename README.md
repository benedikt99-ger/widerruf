# module for OXID eShop 7

[![Latest Version](https://img.shields.io/packagist/v/benedikt99-ger/widerruf?logo=composer&label=latest&include_prereleases&color=orange)](https://packagist.org/packages/benedikt99-ger/widerruf)
[![PHP Version](https://img.shields.io/packagist/php-v/benedikt99-ger/widerruf)](https://github.com/benedikt99-ger/widerruf)

OXID eShop 7 .........

## Features

* own form for german requirement for Widerruf Button


## Compatibility

* Branch main is compatible with OXID Shop compilation 7.1.x and up

## Installation

Module is available on packagist. Install it via composer and activate the module

## Configuration

But a button in your template (near div class="header-container")
Example: 
header.html.twig
```
 {% block layout_header_top %}
	<div class="menu-widerruf pull-left">
	   <a class="btn btn-highlight" href="{{ oViewConf.getBaseDir()|raw }}widerruf/">Vertrag widerrufen</a>
	</div>	
```


```
composer require benedikt99/widerruf
vendor/bin/oe-console oe:module:activate widerruf
```
