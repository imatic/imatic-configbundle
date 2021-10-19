<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\ImaticConfigBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\ImaticConfigBundle\Entity\Config;

class LoadConfigData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = [
            'config.foo' => 'foo',
        ];

        foreach ($data as $key => $value) {
            $manager->persist(new Config($key, $value));
        }

        $manager->flush();
    }
}
