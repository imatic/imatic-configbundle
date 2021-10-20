<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject;

use Imatic\Testing\Test\WebTestCase as BaseWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class WebTestCase extends BaseWebTestCase
{
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }
}
