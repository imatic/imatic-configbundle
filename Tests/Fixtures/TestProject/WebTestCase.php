<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject;

use Imatic\Testing\Test\WebTestCase as BaseWebTestCase;

class WebTestCase extends BaseWebTestCase
{
    protected function setUp(): void
    {
        static::createClient();
    }
}
