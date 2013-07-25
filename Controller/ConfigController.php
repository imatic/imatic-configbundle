<?php
namespace Imatic\Bundle\ConfigBundle\Controller;

use Imatic\Bundle\ConfigBundle\Entity\ConfigManager;
use Imatic\Bundle\ConfigBundle\Provider\ChainProvider;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Configuration\Route("/imatic/config/config")
 */
class ConfigController extends Controller
{
    /**
     * @Configuration\Route
     * @Configuration\Template
     */
    public function editAction()
    {
        //        $this->getConfigManager()->set('prc', 'mrdoprc')->set('soust', 'mrdosoust')->set('curakomrd', 'voprcosoust');
        die(var_dump($this->getConfigManager()->get('prc')));

        $formBuilder = $this->createFormBuilder(null, ['translation_domain' => 'ImaticConfigBundle']);

        foreach ($this->getConfigProvider()->getNodes() as $node) {
            $formBuilder->add($node->getKey(), $node->getType());
        }

        $formBuilder->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary']]);

        return ['form' => $formBuilder->getForm()->createView()];
    }

    /**
     * @return ConfigManager
     */
    private function getConfigManager()
    {
        return $this->get('imatic_config.entity.config_manager');
    }

    /**
     * @return ChainProvider
     */
    private function getConfigProvider()
    {
        return $this->get('imatic_config.provider.chain_provider');
    }
}