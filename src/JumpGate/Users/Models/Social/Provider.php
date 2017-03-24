<?php

namespace JumpGate\Users\Models\Social;

class Provider
{
    /**
     * @var string
     */
    public $driver;

    /**
     * @var array
     */
    public $scopes;

    /**
     * @var array
     */
    public $extras;

    public function __construct($details)
    {
        $this->driver = array_get($details, 'driver', null);
        $this->scopes = array_get($details, 'scopes', []);
        $this->extras = array_get($details, 'extras', []);
    }
}
