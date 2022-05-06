<?php

namespace App\Request\Place;

use App\Request\AbstractBaseRequest;
use Symfony\Component\Validator\Constraints as Assert;

class ScheduleRequest extends AbstractBaseRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Positive
     */
    protected $serviceId;

    /**
     * @Assert\NotBlank
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    protected $date;

    /**
     * @Assert\NotBlank
     * @Assert\Time
     * @var string A "H:i:s" formatted value
     */
    protected $time;

    /**
     * @var string
     */
    protected $observation;
}
