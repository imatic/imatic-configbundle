<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Imatic\Bundle\ConfigBundle\Config\ConfigManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class ConfigController
{
    private ConfigManagerInterface $configManager;

    private FormFactoryInterface $formFactory;

    private EntityManagerInterface $manager;

    private RouterInterface $router;

    private Environment $twig;

    private array $groups = [];

    public function __construct(ConfigManagerInterface $configManager, FormFactoryInterface $formFactory, EntityManagerInterface $manager, RouterInterface $router, Environment $twig)
    {
        $this->configManager = $configManager;
        $this->formFactory = $formFactory;
        $this->manager = $manager;
        $this->router = $router;
        $this->twig = $twig;
    }

    public function edit(Request $request): Response
    {
        $form = $this->createConfigForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->getData() as $key => $value) {
                $this->configManager->setValue($key, $value, false);
            }

            $this->manager->flush();

            return new RedirectResponse($this->router->generate('imatic_config_config'));
        }

        return new Response($this->twig->render('@ImaticConfig/Config/edit.html.twig', [
            'groups' => $this->groups,
            'form' => $form->createView(),
        ]));
    }

    private function createConfigForm(): FormInterface
    {
        $formBuilder = $this->formFactory->createBuilder();

        foreach ($this->configManager->getDefinitions() as $name => $definitions) {
            foreach ($definitions as $key => $definition) {
                $child = strtr($key, '.', ':');

                $formBuilder->add($child, $definition->getType(), $definition->getOptions() + [
                        'label' => $key,
                        'translation_domain' => 'configuration',
                        'property_path' => sprintf('[%s]', $key),
                        'data' => $this->configManager->getValue($key),
                    ]);

                $this->groups[$name][] = $child;
            }
        }

        $formBuilder->add('submit', SubmitType::class, [
            'label' => 'form.submit',
            'translation_domain' => 'ImaticConfigBundle',
        ]);

        return $formBuilder->getForm();
    }
}
