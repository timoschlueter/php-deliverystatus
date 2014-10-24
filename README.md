PHP Delivery Status
=========

PHP Delivery Status is a class to retrieve deliveries tracked through [Juneclouds "Delivery Status"](http://www.junecloud.com/ds/) service  via PHP.
Its no official class and i am not affiliated with Junecloud. This is a quick and dirty hack, not an API.

REQUIREMENTS
-------

This class requieres at least PHP 5.2 and the JSON and CURL (with SSL) extension.

INSTALLATION
--------

Simply include the class and you are good to go.

FUNCTIONS
--------

### setUsername()

**[Mandatory]** Sets the username used for logging into Junecloud

### setPassword

**[Mandatory]** Sets the password used for logging into Junecloud

### setTimeout

Sets the connection timeout for cURL

### login

Logs into Junecloud (requires valid username and password)

### Logout

Logs out and closes the cURL connection

### getUsername

Returns the Username used by Junecloud

### getDeliveries

Returns all your deliveries as an array of objects. 

EXAMPLE
--------

This is an example which logs into Delivery Status on Junecloud, fetches all your delivies and return them as an array of objects.

```php
	<?php
		include('class.deliverystatus.php');
		
		$ds = New Deliverystatus();
		$ds->setUsername('USERNAME');
		$ds->setPassword('PASSWORD');
		$ds->setTimeout(20);
		
		$ds->login();
		$myDeliveries = $ds->getDeliveries();
		$ds->logout();
		
		echo "<pre>";
		var_dump($myDeliveries);
		echo "</pre>";
	?>
```

#### Example output:

	array(3) {
	  [0]=>
	  object(stdClass)#9 (4) {
		["title"]=>
		string(11) "iPad Air 2 "
		["tracking_url"]=>
		string(56) "https://tracking.hermesworld.com/?TrackID=71295223100561"
		["tracking_number"]=>
		string(14) "71295223100561"
		["information"]=>
		string(10) "www.hlg.de"
	  }
	  [1]=>
	  object(stdClass)#18 (4) {
		["title"]=>
		string(17) "Bench Jacke eBay "
		["tracking_url"]=>
		string(83) "http://nolp.dhl.de/nextt-online-public/set_identcodes.do?idc=966939672912&zip=95028"
		["tracking_number"]=>
		string(12) "966939672912"
		["information"]=>
		string(18) "nolp.dhl.de, 95028"
	  }
	  [2]=>
	  object(stdClass)#17 (4) {
		["title"]=>
		string(15) "TNF Jacke eBay "
		["tracking_url"]=>
		string(83) "http://nolp.dhl.de/nextt-online-public/set_identcodes.do?idc=966939632104&zip=80469"
		["tracking_number"]=>
		string(12) "966939632104"
		["information"]=>
		string(18) "nolp.dhl.de, 80469"
	  }
	}

LICENSE
-------

The MIT License (MIT)

Copyright (c) 2014 Timo Schlueter <timo.schlueter@me.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.