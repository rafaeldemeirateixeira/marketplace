<?php

namespace App\Request\Category;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceCategoryRequest extends AbstractBaseRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    protected $providerCategoryId;

    /**
     * @Assert\NotBlank
     */
    protected $name;
}
