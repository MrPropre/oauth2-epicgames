# Epic Games Provider for OAuth 2.0 Client
[![Latest Version](https://img.shields.io/github/release/MrPropre/oauth2-epicgames.svg?style=flat-square)](https://github.com/MrPropre/oauth2-epicgames/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/MrPropre/oauth2-epicgames/master.svg?style=flat-square)](https://travis-ci.org/MrPropre/oauth2-epicgames)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/MrPropre/oauth2-epicgames.svg?style=flat-square)](https://scrutinizer-ci.com/g/MrPropre/oauth2-epicgames/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/MrPropre/oauth2-epicgames.svg?style=flat-square)](https://scrutinizer-ci.com/g/MrPropre/oauth2-epicgames)
[![Total Downloads](https://img.shields.io/packagist/dt/mrpropre/oauth2-epicgames.svg?style=flat-square)](https://packagist.org/packages/mrpropre/oauth2-epicgames)

This package provides Epic Games OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

This package is compliant with [PSR-4][], [PSR-7][], and [PSR-12][]. If you notice compliance oversights,
please send a patch via pull request.

[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
[PSR-7]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-7-http-message.md
[PSR-12]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-12-extended-coding-style-guide.md

## Installation

To install, use composer:

```
composer require mrpropre/oauth2-epicgames
```

## Requirements

The following versions of PHP are supported.

* PHP 7.3
* PHP 7.4
* PHP 8.0

## Usage

Usage is the same as The League's OAuth client, using `\MrPropre\OAuth2\Client\Provider\EpicGames` as the provider.

### Authorization Code Flow

```php
require __DIR__ . '/vendor/autoload.php';

use MrPropre\OAuth2\Client\Provider\EpicGames;
use League\OAuth2\Client\OptionProvider\HttpBasicAuthOptionProvider;

session_start(); // Remove if session.auto_start=1 in php.ini

$provider = new EpicGames([
    'clientId'          => '{epicgames-client-id}',
    'clientSecret'      => '{epicgames-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
], [
    'optionProvider' => new HttpBasicAuthOptionProvider()
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getUsername());

    } catch (\Exception $e) {

        // Failed to get user details
        exit('Something went wrong: ' . $e->getMessage());
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Managing Scopes

When creating your Epic Games authorization URL, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => ['basic_profile','friends_list','presence'] // array or string
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```
If neither are defined, the provider will utilize internal defaults.

At the time of authoring this documentation, the [following scopes are available](https://dev.epicgames.com/docs/services/en-US/EpicAccountServices/GettingStarted/index.html#applicationpermissions).

- basic_profile
- presence
- friends_list

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/MrPropre/oauth2-epicgames/blob/master/CONTRIBUTING.md) for details.


## Credits

- [Adrien Alais](https://github.com/MrPropre)
- [All Contributors](https://github.com/MrPropre/oauth2-epicgames/contributors)


## License

The MIT License (MIT). Please see [License File](https://github.com/MrPropre/oauth2-epicgames/blob/master/LICENSE) for more information.