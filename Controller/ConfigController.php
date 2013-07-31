<?php
namespace Imatic\Bundle\ConfigBundle\Controller;

use Imatic\Bundle\ConfigBundle\Manager\ConfigManager;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Configuration\Route("/imatic/config/config")
 */
class ConfigController extends Controller
{
    /** @var array */
    private $groups;

    /**
     * @param Request $request
     * @return array
     * @Configuration\Route
     * @Configuration\Template
     */
    public function editAction(Request $request)
    {
        $configManager = $this->getConfigManager();
        $form = $this->createConfigForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $value) {
                $configManager->setValue($key, $value, false);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('imatic_config_config_edit'));
        }

        return [
            'groups' => $this->groups,
            'form' => $form->createView()
        ];
    }

    /**
     * @return Form
     */
    private function createConfigForm()
    {
        $configManager = $this->getConfigManager();
        $formBuilder = $this->createFormBuilder();
        $this->groups = [];

        foreach ($configManager->getDefinitions() as $name => $definitions) {
            foreach ($definitions as $definition) {
                /* @var $definition Definition */
                $key = sprintf('%s.%s', $name, $definition->getKey());
                $child = strtr($key, '.', ':');
                $formBuilder->add($child, $definition->getType(), $definition->getOptions() + [
                    'label' => ' ' . $key,
                    'help_inline' => $key,
                    'translation_domain' => 'config',
                    'property_path' => sprintf('[%s]', $key),
                    'data' => $configManager->getValue($key)
                ]);
                $this->groups[$name][] = $child;
            }
        }

        return $formBuilder->getForm();
    }

    /**
     * @return ConfigManager
     */
    private function getConfigManager()
    {
        return $this->get('imatic_config.manager.config_manager');
    }
}