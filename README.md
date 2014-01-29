This is the official wrapper for the ShippingEasy API. Currently the API only exposes several functions so this wrapper only handles the following calls:

* Adding an order to a ShippingEasy store
* Cancelling an order before it has been shipped

We will keep this library up to date as we expand our API offerings.

## Setup

### Installation

Obtain the latest version of the ShippingEasy PHP bindings with:

    git clone https://github.com/ShippingEasy/shipping_easy-php

To get started, add the following to your PHP script:

    require_once("/path/to/shipping_easy-php/lib/ShippingEasy.php");

### Configuration

You will need a ShippingEasy API key and secret to sign your API requests. These can be found in your store's settings (https://app.shippingeasy.com/settings/stores). Please note that these credentials are for a specific store on a ShippingEasy account and you could potentially have multiple stores.

Once you have the credentials, add them to the libary's configuration:

    ShippingEasy::setApiKey('d8e8fca2dc0f896fd7cb4cb0031ba249');
    ShippingEasy::setApiSecret('XXX');

If you are a 3rd party plugin developer and have a staging account with ShippingEasy, you can change the endpoint like so:

    ShippingEasy::setApiBase('https://staging.shippingeasy.com');

## API Calls

### Adding an order

To add an order to a store, first instantiate a new order object with an associative array of the order data. (A comprehensive list of the data attributes and their definitions can be found below.)

    $order = new ShippingEasy_Order(array("external_order_identifier" => "ABC123", "subtotal_including_tax" => "12.38", ....));

Then simply call create to execute the remote call:

    $order->create();
    
If successful the call will return a JSON hash with the ShippingEasy order ID, as well as the external order identifier originally supplied in your call.

    { "order" => { "id" => "27654", "external_order_identifier" => "ABC123" } }
    
#### Possible Exceptions

##### ShippingEasy_AuthenticationError
Your credentials could not be authenticated.

##### ShippingEasy_InvalidRequestError
The order could not be created on the server for one or more of the following reasons:

* The JSON payload could not be parsed.
* One or more of the supplied data attributes failed validation and is missing or incorrect.
* An order with the supplied external_order_identifier already exists for that store.

The exception will contain a message that indicates which of these conditions failed.

### Cancelling an order

Sometimes an e-commerce system will mark an order as shipped outside of the ShippingEasy system. Therefore an API call is required to remove this order from ShippingEasy so that it is not double-shipped. 

First create a new cancellation object with the e-commerce order identifier used to create the order in ShippingEasy:

    $cancellation = new ShippingEasy_Cancellation("ABC123");

Then simply call create to execute the remote call:

    $cancellation->create();
    
If successful the call will return a JSON hash with the ShippingEasy order ID, as well as the external order identifier originally supplied in your call.

    { "order" => { "id" => "27654", "external_order_identifier" => "ABC123" } }
    
#### Possible Exceptions

##### ShippingEasy_AuthenticationError
Your credentials could not be authenticated.

##### ShippingEasy_InvalidRequestError
The cancellation could not complete for one or more of the following reasons:

* The order could not be found.
* The order has already been marked as shipped in the ShippingEasy system and cannot be cancelled.

The exception will contain a message that indicates which of these conditions failed.

### Tests

In order to run tests you have to install SimpleTest (http://packagist.org/packages/vierbergenlars/simpletest) via Composer (http://getcomposer.org/) (recommended way):

    composer.phar update --dev

Run test suite:

    php ./test/ShippingEasy.php
