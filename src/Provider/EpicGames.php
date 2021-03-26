<?php

namespace MrPropre\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class EpicGames extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain
     *
     * @var string
     */
    public $domain = 'https://www.epicgames.com';

    /**
     * API domain
     *
     * @var string
     */
    public $apiDomain = 'https://api.epicgames.dev';

    /**
     * Get authorization URL to begin OAuth flow
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . '/id/authorize';
    }

    /**
     * Get access token URL to retrieve token
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->apiDomain . '/epic/oauth/v1/token';
    }

    /**
     * Get provider URL to request user details
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->apiDomain . '/epic/oauth/v1/userInfo';
    }

    protected function getDefaultScopes(): array
    {
        return [
            'basic_profile'
        ];
    }

    protected function getScopeSeparator(): string
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                $data['errorMessage'] ?? $response->getReasonPhrase(),
                $response->getStatusCode(),
                (string) $response->getBody()
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token): EpicGamesResourceOwner
    {
        return new EpicGamesResourceOwner($response);
    }
}
