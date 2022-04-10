<?php

namespace App\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\LazyResponseException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractBaseRequest
{
    /** @var array */
    private $_bodyData = [];

    /** @var array */
    private $validated = [];

    /** @var Request */
    private $_request;

    /** @var array */
    private $hideData = [
        'password',
        'password_confirmation',
        'token'
    ];

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(
        protected ValidatorInterface $validator
    ) {
        $this->populate();

        if ($this->autoValidateRequest()) {
            $this->validate();
        }
    }

    /**
     * @return void
     */
    public function validate()
    {
        $errors = $this->validator->validate($this);
        $messages = [
            'code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'validation_failed',
            'errors' => []
        ];

        /** @var \Symfony\Component\Validator\ConstraintViolation  */
        foreach ($errors as $message) {
            $value = !in_array($message->getPropertyPath(), $this->hideData)
                ? $message->getInvalidValue()
                : null;
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $value,
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            $response = new JsonResponse($messages, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
            throw new LazyResponseException($response);
        }
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        if ($this->_request instanceof Request) {
            return $this->_request;
        }

        return Request::createFromGlobals();
    }

    /**
     * @return void
     */
    protected function populate(): void
    {
        $this->_request = $this->getRequest();
        $this->_bodyData = $this->_request->toArray();
        $this->_request->request->replace($this->_bodyData);

        foreach ($this->_bodyData as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
                $this->validated["{$property}"] = $value;
            }
        }
    }

    /**
     * @return array
     */
    public function validated(): array
    {
        return $this->validated;
    }

    /**
     * @return boolean
     */
    protected function autoValidateRequest(): bool
    {
        return true;
    }

}
