<?php
namespace Imatic\Bundle\ConfigBundle\Manager;

use Imatic\Bundle\ConfigBundle\Exception\InvalidValueException;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ValueTransformer
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory) {
        $this->formFactory = $formFactory;
    }

    /**
     * @param Definition $definition
     * @param mixed $value
     * @return scalar
     * @throws InvalidValueException
     */
    public function transform(Definition $definition, $value)
    {
        $form = $this->createForm($definition->getType(), $definition->getOptions(), $value);
        $viewData = $form->getViewData();

        if (!is_scalar($viewData)) {
            throw new InvalidValueException(sprintf(
                'Invalid view value "%s". The form must be configured to return scalar view values.',
                json_encode($viewData)
            ));
        }

        $form->submit($viewData);
        $this->validateForm($form);

        return $viewData;
    }

    /**
     * @param Definition $definition
     * @param scalar $value
     * @return mixed
     * @throws InvalidValueException
     */
    public function reverseTransform(Definition $definition, $value)
    {
        $form = $this->createForm($definition->getType(), $definition->getOptions());
        $form->submit($value);
        $this->validateForm($form);

        return $form->getData();
    }

    /**
     * @param string $type
     * @param array $options
     * @param mixed $value
     * @return Form
     * @throws InvalidValueException
     */
    private function createForm($type, array $options, $value = null)
    {
        try {
            $form = $this->formFactory->create($type, $value, $options);
        } catch (TransformationFailedException $e) {
            throw new InvalidValueException($e->getMessage());
        }

        return $form;
    }

    /**
     * @param FormInterface $form
     * @throws InvalidValueException
     */
    private function validateForm(FormInterface $form)
    {
        foreach ($form->getErrors() as $error) {
            throw new InvalidValueException($error->getMessage());
        }
    }
}