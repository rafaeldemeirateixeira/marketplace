<?php

namespace App\Request\Category;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ProviderCategoryRequest extends AbstractBaseRequest
{
    /**
     * @Assert\NotBlank
     */
    protected $name;
}
