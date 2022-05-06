<?php

namespace App\Request\User;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UserRatingRequest extends AbstractBaseRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    protected $userRatedId;

    /**
     * @Assert\NotBlank
     *
     * @Assert\GreaterThanOrEqual(1)
     * @Assert\LessThanOrEqual(5)
     */
    protected $rate;
}
