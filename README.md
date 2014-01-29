### Installation

Obtain the latest version of the ShippingEasy PHP bindings with:

    git clone https://github.com/ShippingEasy/shipping_easy-php

To get started, add the following to your PHP script:

    require_once("/path/to/shipping_easy-php/lib/ShippingEasy.php");

Simple usage looks like:

    ShippingEasy::setApiKey('d8e8fca2dc0f896fd7cb4cb0031ba249');
    ShippingEasy::setApiSecret('f01d4c9bb1dec1a5f46d2a3ba9dfbdc6f3c145604440fb145677eb7ef3af9731');



### Tests

In order to run tests you have to install SimpleTest (http://packagist.org/packages/vierbergenlars/simpletest) via Composer (http://getcomposer.org/) (recommended way):

    composer.phar update --dev

Run test suite:

    php ./test/ShippingEasy.php
