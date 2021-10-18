<?php declare(strict_types=1);
namespace Imatic\Bundle\ConfigBundle\Config;

use Imatic\Bundle\ConfigBundle\Exception\InvalidValueException;
use Imatic\Bundle\ConfigBundle\Provider\Definition;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ValueTransformer
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param mixed $value
     *
     * @return scalar
     *
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
     * @param scalar $value
     *
     * @return mixed
     *
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
     * @param mixed $value
     *
     * @throws InvalidValueException
     */
    private function createForm(string $type, array $options = [], $value = null): FormInterface
    {
        try {
            $form = $this->formFactory->create($type, $value, $options);
        } catch (TransformationFailedException $e) {
            throw new InvalidValueException($e->getMessage());
        }

        return $form;
    }

    /**
     * @throws InvalidValueException
     */
    private function validateForm(FormInterface $form)
    {
        foreach ($form->getErrors() as $error) {
            throw new InvalidValueException($error->getMessage());
        }
    }
}
