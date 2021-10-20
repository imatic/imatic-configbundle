<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Functional\Controller;

use Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\WebTestCase;

class ConfigControllerTest extends WebTestCase
{
    public function testEdit()
    {
        $this->client->request('GET', '/config');
        $this->client->submitForm('Save', ['form[config:foo]' => 'test']);
        $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
    }
}
