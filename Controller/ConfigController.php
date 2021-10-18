<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Controller;

use Imatic\Bundle\ConfigBundle\Config\ConfigManager;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Configuration\Route("/imatic/config/config")
 */
class ConfigController extends Controller
{
    /** @var array */
    private $groups;

    /**
     * @param Request $request
     *
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
            'form' => $form->createView(),
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
            foreach ($definitions as $key => $definition) {
                /* @var $definition Definition */
                $child = strtr($key, '.', ':');
                $formBuilder->add($child, $definition->getType(), $definition->getOptions() + [
                        'label' => $key,
                        'help_label' => $key,
                        'translation_domain' => 'configuration',
                        'property_path' => sprintf('[%s]', $key),
                        'data' => $configManager->getValue($key),
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
        return $this->get('imatic_config.config_manager');
    }
}
