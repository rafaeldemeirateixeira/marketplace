<?php

namespace App\Request\Place;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceRequest extends AbstractBaseRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    protected $serviceCategoryId;

    /**
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @Assert\NotBlank
     */
    protected $description;

    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    protected $amount;
}
