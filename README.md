Mardy-Git HMAC Guzzle Plugin
============================

This plugin will inject the HMAC hash/data/time values directly into the headers of the Guzzle request.

Mardy-Git Hmac: https://github.com/mardy-git/hmac

Guzzle: https://github.com/guzzle/guzzle

Installation
--------------

To install this use composer by adding

    "require": {
        "guzzle/plugin-mardy-hmac": "dev-master"
    }

to your composer.json file

Usage Example
--------------------
```php
use Mardy\Hmac\Manager;
use Mardy\Hmac\Adapters\Hash;
use Mardy\HmacPlugins\HmacGuzzlePlugin;
use Guzzle\Http\Client;

$manager = new Manager(new Hash);
$client = new Client('http://127.0.0.1');

$this->manager->ttl(0)
              ->data('test') //string containing either a URI or other related data string
              ->time(1396901689) //timestamp for now or whatever other time you want to base the hmac on
              ->key('1234'); //a secure key string that is kept private in the database or config files

$client->addSubscriber(new HmacGuzzlePlugin($this->manager));

$request = $client->get();
$request->send();

//to check what data has been placed in the header it can be retrieved with by using the following code
echo $request->getHeader('Mardy-Hmac-Hash');
echo $request->getHeader('Mardy-Hmac-Time');
echo $request->getHeader('Mardy-Hmac-Data');
```
