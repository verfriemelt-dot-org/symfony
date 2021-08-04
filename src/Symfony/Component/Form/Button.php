<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form;

use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\Form\Exception\BadMethodCallException;

/**
 * A form button.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Button implements \IteratorAggregate, FormInterface
{
    private ?FormInterface $parent = null;
    private FormConfigInterface $config;
    private bool $submitted = false;

    /**
     * Creates a new button from a form configuration.
     */
    public function __construct(FormConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Unsupported method.
     *
     * @return bool Always returns false
     */
    public function offsetExists(mixed $offset): bool
    {
        return false;
    }

    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function offsetGet(mixed $offset): mixed
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }

    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }

    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }

    /**
     * {@inheritdoc}
     */
    public function setParent(FormInterface $parent = null)
    {
        if ($this->submitted) {
            throw new AlreadySubmittedException('You cannot set the parent of a submitted button.');
        }

        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function add(string|FormInterface $child, string $type = null, array $options = [])
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }

    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function get(string $name)
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }

    /**
     * Unsupported method.
     *
     * @return bool Always returns false
     */
    public function has(string $name)
    {
        return false;
    }

    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @throws BadMethodCallException
     */
    public function remove(string $name)
    {
        throw new BadMethodCallException('Buttons cannot have children.');
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors(bool $deep = false, bool $flatten = true)
    {
        return new FormErrorIterator($this, []);
    }

    /**
     * Unsupported method.
     *
     * This method should not be invoked.
     *
     * @return $this
     */
    public function setData(mixed $modelData)
    {
        // no-op, called during initialization of the form tree
        return $this;
    }

    /**
     * Unsupported method.
     */
    public function getData()
    {
        return null;
    }

    /**
     * Unsupported method.
     */
    public function getNormData()
    {
        return null;
    }

    /**
     * Unsupported method.
     */
    public function getViewData()
    {
        return null;
    }

    /**
     * Unsupported method.
     *
     * @return array Always returns an empty array
     */
    public function getExtraData()
    {
        return [];
    }

    /**
     * Returns the button's configuration.
     *
     * @return FormConfigInterface The configuration instance
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Returns whether the button is submitted.
     *
     * @return bool true if the button was submitted
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }

    /**
     * Returns the name by which the button is identified in forms.
     *
     * @return string The name of the button
     */
    public function getName()
    {
        return $this->config->getName();
    }

    /**
     * Unsupported method.
     */
    public function getPropertyPath()
    {
        return null;
    }

    /**
     * Unsupported method.
     *
     * @throws BadMethodCallException
     */
    public function addError(FormError $error)
    {
        throw new BadMethodCallException('Buttons cannot have errors.');
    }

    /**
     * Unsupported method.
     *
     * @return bool Always returns true
     */
    public function isValid()
    {
        return true;
    }

    /**
     * Unsupported method.
     *
     * @return bool Always returns false
     */
    public function isRequired()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isDisabled()
    {
        if ($this->parent && $this->parent->isDisabled()) {
            return true;
        }

        return $this->config->getDisabled();
    }

    /**
     * Unsupported method.
     *
     * @return bool Always returns true
     */
    public function isEmpty()
    {
        return true;
    }

    /**
     * Unsupported method.
     *
     * @return bool Always returns true
     */
    public function isSynchronized()
    {
        return true;
    }

    /**
     * Unsupported method.
     */
    public function getTransformationFailure()
    {
        return null;
    }

    /**
     * Unsupported method.
     *
     * @throws BadMethodCallException
     */
    public function initialize()
    {
        throw new BadMethodCallException('Buttons cannot be initialized. Call initialize() on the root form instead.');
    }

    /**
     * Unsupported method.
     *
     * @throws BadMethodCallException
     */
    public function handleRequest(mixed $request = null)
    {
        throw new BadMethodCallException('Buttons cannot handle requests. Call handleRequest() on the root form instead.');
    }

    /**
     * Submits data to the button.
     *
     * @return $this
     *
     * @throws Exception\AlreadySubmittedException if the button has already been submitted
     */
    public function submit(array|string|null $submittedData, bool $clearMissing = true)
    {
        if ($this->submitted) {
            throw new AlreadySubmittedException('A form can only be submitted once.');
        }

        $this->submitted = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoot()
    {
        return $this->parent ? $this->parent->getRoot() : $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isRoot()
    {
        return null === $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    public function createView(FormView $parent = null)
    {
        if (null === $parent && $this->parent) {
            $parent = $this->parent->createView();
        }

        $type = $this->config->getType();
        $options = $this->config->getOptions();

        $view = $type->createView($this, $parent);

        $type->buildView($view, $this, $options);
        $type->finishView($view, $this, $options);

        return $view;
    }

    /**
     * Unsupported method.
     *
     * @return int Always returns 0
     */
    public function count(): int
    {
        return 0;
    }

    /**
     * Unsupported method.
     *
     * @return \EmptyIterator Always returns an empty iterator
     */
    public function getIterator(): \EmptyIterator
    {
        return new \EmptyIterator();
    }
}
