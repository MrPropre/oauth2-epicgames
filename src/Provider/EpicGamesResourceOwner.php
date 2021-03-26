<?php

namespace MrPropre\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

class EpicGamesResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * @var array
     */
    protected $response;

    /**
     * Creates new resource owner.
     */
    public function __construct(array $response = [])
    {
        $this->response = $response;
    }

    /**
     * Get resource owner ID
     */
    public function getId(): ?string
    {
        return $this->getValueByKey($this->response, 'sub');
    }

    /**
     * Get resource owner username
     */
    public function getUsername(): ?string
    {
        return $this->getValueByKey($this->response, 'preferred_username');
    }

    /**
     * Returns the raw resource owner response.
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
