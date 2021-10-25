<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject;

use Imatic\Testing\Test\TestKernel as BaseTestKernel;

class TestKernel extends BaseTestKernel
{
    public function registerBundles(): array
    {
        $parentBundles = parent::registerBundles();

        $bundles = [
            new \Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new \Imatic\Bundle\ConfigBundle\ImaticConfigBundle(),
            new \Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\ImaticConfigBundle\AppImaticConfigBundle(),
        ];

        return array_merge($parentBundles, $bundles);
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }
}
