<?php

namespace App\Request\User;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UserProviderRequest extends AbstractBaseRequest
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

    /**
     * @Assert\NotBlank
     */
    protected $phone;
}
