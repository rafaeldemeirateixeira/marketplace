<?php

namespace App\Request\User;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class UserAddressRequest extends AbstractBaseRequest
{
    /**
     * @Assert\NotBlank
     */
    protected $address;

    /**
     * @var int
     */
    protected $number;

    /**
     * @Assert\NotBlank
     */
    protected $district;

    /**
     * @Assert\NotBlank
     */
    protected $city;

    /**
     * @Assert\NotBlank
     */
    protected $state;

    /**
     * @Assert\NotBlank
     */
    protected $zipCode;

    /**
     * @var string
     */
    protected $complement;

    /**
     * @Assert\NotBlank
     */
    protected $latitude;

    /**
     * @Assert\NotBlank
     */
    protected $longitude;
}
