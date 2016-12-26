<?php

namespace ZFTest\OAuth2\Doctrine\ORM;

class RoleTest extends AbstractTest
{
    /** @dataProvider provideStorage */
    public function testSetAccessToken()
    {
        die('hit test');

        // assert token we are about to add does not exist
        $token = $storage->getAccessToken('newtoken');
        $this->assertFalse($token);

        // add new token; get user from previous token
        $testToken = $storage->getAccessToken('testtoken');


        $expires = time() + 20;
        $success = $storage->setAccessToken('newtoken', 'oauth_test_client', $testToken['user_id'], $expires);
        $this->assertTrue($success);

        $token = $storage->getAccessToken('newtoken');
        $this->assertNotNull($token);
        $this->assertArrayHasKey('access_token', $token);
        $this->assertArrayHasKey('client_id', $token);
        $this->assertArrayHasKey('user_id', $token);
        $this->assertArrayHasKey('expires', $token);
        $this->assertEquals($token['access_token'], 'newtoken');
        $this->assertEquals($token['client_id'], 'oauth_test_client');
        $this->assertEquals($token['user_id'], $testToken['user_id']);
        $this->assertEquals($token['expires'], $expires);

        // change existing token
        $expires = time() + 42;
        $success = $storage->setAccessToken('newtoken', 'oauth_test_client2', $testToken['user_id'], $expires);
        $this->assertTrue($success);

        $token = $storage->getAccessToken('newtoken');
        $this->assertNotNull($token);
        $this->assertArrayHasKey('access_token', $token);
        $this->assertArrayHasKey('client_id', $token);
        $this->assertArrayHasKey('user_id', $token);
        $this->assertArrayHasKey('expires', $token);
        $this->assertEquals($token['access_token'], 'newtoken');
        $this->assertEquals($token['client_id'], 'oauth_test_client2');
        $this->assertEquals($token['user_id'], $testToken['user_id']);
        $this->assertEquals($token['expires'], $expires);

        $this->assertTrue($storage->setAccessToken('event_stop_propagation', '', '', '', ''));
        $this->assertTrue($storage->getAccessToken('event_stop_propagation'));
    }
}
