<?php

use Mardy\Hmac\Manager;
use Mardy\Hmac\Adapters\Hash;
use Mardy\HmacPlugins\HmacGuzzlePlugin;
use Guzzle\Http\Client;
use Guzzle\Plugin\Mock\MockPlugin;

class HmacGuzzlePluginTest extends \PHPUnit_Framework_Testcase
{
    protected $manager;

    public function setup()
    {
        $this->manager = new Manager(new Hash);
    }

    public function testGuzzlePlugin()
    {
        $this->manager->ttl(0)
                      ->data('test')
                      ->time(1396901689)
                      ->key('1234');

        $client = new Client('http://127.0.0.1');

        $client->addSubscriber(new HmacGuzzlePlugin($this->manager));
        $client->addSubscriber(new MockPlugin);

        $request = $client->get();
        $request->send();

        $this->assertSame('db02255882fecfdbb04c882ad598e8caa1956a27a98c02f84153ecb9b263ee75d1dadf3bd6d22d725793a27e04d041db5a93d83432d266600e1a366e5e42bee2', (string) $request->getHeader('Mardy-Hmac-Hash'));
        $this->assertSame('1396901689', (string) $request->getHeader('Mardy-Hmac-Time'));
        $this->assertSame('test', (string) $request->getHeader('Mardy-Hmac-Data'));
    }
}
