<?php

namespace ServerGrove\SGLiveChatBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class ChatControllerTest extends WebTestCase
{

    protected function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testIndexGet()
    {
        /* @var $client Symfony\Bundle\FrameworkBundle\Client */
        $client = $this->createClient();

        /* @var $crawler Symfony\Component\DomCrawler\Crawler */
        $crawler = $client->request('GET', '/sglivechat');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'GET response not successful');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Question")')->count(), 'HTML not contains "Question"');

        $client->submit($crawler->selectButton('Start Chat')->form(), array('name' => 'Ismael', 'email' => 'ismael@servergrove.com', 'question' => 'This is my comment'));
        $this->assertTrue($client->getResponse()->isRedirect(), 'Is not redirecting');
        unset($client, $crawler);
    }

    public function testIndexPost()
    {
        /* @var $client Symfony\Bundle\FrameworkBundle\Client */
        $client = $this->createClient();

        /* @var $crawler Symfony\Component\DomCrawler\Crawler */
        $crawler = $client->request('POST', '/sglivechat', array('name' => 'Ismael', 'email' => 'ismael@servergrove.com', 'question' => 'This is my comment'));

        $this->assertTrue($client->getResponse()->isRedirect(), 'Is not redirecting');
        unset($client, $crawler);
    }

    public function testLoad()
    {
        /* @var $client Symfony\Bundle\FrameworkBundle\Client */
        $client = $this->createClient();

        /* @var $crawler Symfony\Component\DomCrawler\Crawler */
        $crawler = $client->request('GET', '/sglivechat/123whatever321/load');

        $this->assertTrue($client->getResponse()->isRedirect(), 'Is not redirecting');

        $client->request('POST', '/sglivechat', array('name' => 'Ismael', 'email' => 'ismael@servergrove.com', 'question' => 'This is my comment'));

        /* @var $crawler Symfony\Component\DomCrawler\Crawler */
        $crawler = $client->request('GET', '/sglivechat/'.$client->getRequest()->getSession()->get('chatsession') .'/load');

        $this->assertTrue($client->getResponse()->isRedirect(), 'Is not redirecting');
    }

}