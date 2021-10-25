<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Tests\Fixtures\TestProject\ImaticConfigBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @Route("/config/get")
     */
    public function getValue(): Response
    {
        return $this->render('@AppImaticConfig/get_value.html.twig');
    }
}
