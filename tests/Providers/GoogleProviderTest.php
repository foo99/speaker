<?php

namespace duncan3dc\Speaker\Test\Providers;

use duncan3dc\Speaker\Providers\GoogleProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Message\Response;
use Mockery;

class GoogleProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = Mockery::mock(Client::class);
    }


    public function tearDown()
    {
        Mockery::close();
    }


    public function testTextToSpeech()
    {
        $provider = new GoogleProvider("en");
        $provider->setClient($this->client);

        $response = Mockery::mock(Response::class);
        $response->shouldReceive("getStatusCode")->once()->andReturn("200");
        $response->shouldReceive("getBody")->once()->andReturn("mp3");

        $this->client->shouldReceive("get")
            ->once()
            ->with("http://translate.google.com/translate_tts?q=Hello&tl=en&client=tw-ob&ie=UTF-8")
            ->andReturn($response);

        $this->assertSame("mp3", $provider->textToSpeech("Hello"));
    }


    public function testSetLanguage()
    {
        $provider = new GoogleProvider;
        $provider->setClient($this->client);

        $provider->setLanguage("fr");

        $response = Mockery::mock(Response::class);
        $response->shouldReceive("getStatusCode")->once()->andReturn("200");
        $response->shouldReceive("getBody")->once()->andReturn("mp3");

        $this->client->shouldReceive("get")
            ->once()
            ->with("http://translate.google.com/translate_tts?q=Hello&tl=fr&client=tw-ob&ie=UTF-8")
            ->andReturn($response);

        $this->assertSame("mp3", $provider->textToSpeech("Hello"));
    }


    public function testSetLanguageFailure()
    {
        $provider = new GoogleProvider;
        
        $availableLanguages = $provider->getAvailableLanguages();

        $this->setExpectedException("InvalidArgumentException", "Unexpected language code (nope), all the available languages (" . implode(", ", $availableLanguages) . ")");
        $provider->setLanguage("nope");
    }


    public function testGetOptions()
    {
        $provider = new GoogleProvider;

        $options = [
            "language"  =>  "en",
        ];

        $this->assertSame($options, $provider->getOptions());
    }


    public function testSendRequestFailure()
    {
        $provider = new GoogleProvider;

        $this->setExpectedException("InvalidArgumentException", "Only messages under 100 characters are supported");
        $provider->textToSpeech("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur accumsan laoreet sapien, eget posuere");
    }
}
