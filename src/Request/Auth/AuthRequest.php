<?php

namespace App\Request\Auth;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class AuthRequest extends AbstractBaseRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Email(
     *      message = "The email '{{ value }}' is not a valid email."
     * )
     */
    protected $email;

    /**
     * @Assert\NotBlank
     * @Assert\Regex(
     *      pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,16}$/",
     *      message="Minimum 8 and maximum 16 characters, at least one uppercase letter, one lowercase letter, one number and one special character"
     * )
     */
    protected $password;
}
