# Nylas PHP SDK for api version 3

This is the GitHub repository for the Nylas PHP SDK.

The Nylas Communications Platform provides REST APIs for [Email](https://developer.nylas.com/docs/v3/email/), [Calendar](https://developer.nylas.com/docs/v3/calendar/), and [Contacts](https://developer.nylas.com/docs/v3/contacts/), and the Nylas SDK is the quickest way to build your integration using PHP.

Here are some resources to help you get started:

- [Sign up for your free Nylas account](https://dashboard-v3.nylas.com/register)
- [Nylas API v3 Quickstart Guide](https://developer.nylas.com/docs/v3/getting-started/quickstart/)
- [Nylas API Reference](https://developer.nylas.com/docs/v3/api-references/)

## ⚙️ Install
This library is available on https://packagist.org/packages/brainstream/nylas-php</br>
You can install it by running

```shell
composer require brainstream/nylas-php
```

## ⚡️Usage

To use this SDK, you must first [get a free Nylas account](https://dashboard-v3.nylas.com/register).

Then, follow the Quickstart guide to [set up your first app and get your API keys](https://developer.nylas.com/docs/v3/getting-started/quickstart/).

### 🚀 Making Your First Request

You use the `NylasClient` object to make requests to the Nylas API. The SDK is organized into different resources, each of which has methods to make requests to the API. Each resource is available through the `NylasClient` object configured with your API key.

For example, first configure client object like below:

```php
use Nylas\Client;

require __DIR__ . '/vendor/autoload.php';

$options = [
    'api_key'   => 'NYLAS_API_KEY',
    'client_id' => 'NYLAS_CLIENT_ID',
    'region'    => 'NYLAS_REGION', // optional - Default "us"
];

$nylasClient = new Client($options);
```
Then use it for make consecutive api requests.

## Administrative Api info.

### Application api
Application api could be like:

```php
try {
    $applications = $nylasClient->Administration->Application->list();
    print_r($applications);
} catch (GuzzleException) {}
```

### Authentication api
Nylas support 2 types of user authentication
1. Nylas hosted auth(OAuth 2.0)
2. Custom auth


### Grant api
Grants associate with an application.
It could be like:

```php
try {
    $grants = $nylasClient->Administration->Grants->list();
    print_r($grants);
} catch (GuzzleException) {}
```

### Connectors api
Connectors(providers) of an application.

```php
try {
    $connectors = $nylasClient->Administration->Connectors->list();
    print_r($connectors);
} catch (GuzzleException) {}
```

### Connectors Credential api
```php
try {
    $credentials = $nylasClient->Administration->ConnectorsCredentials->list(
        PROVIDER_STRING
    );
    print_r($credentials);
} catch (GuzzleException) {}

//NOTE: Replace "PROVIDER_STRING" with real provider string
```

## Email, Calender and Draft apis usage

### Email api
```php
try {
    $messages = $nylasClient->Messages->Message->list(
        GRANT_ID_STRING
    );
    print_r($messages);
} catch (GuzzleException) {}

//NOTE: Replace "GRANT_ID_STRING" with real grant id
```

### Calendar api
```php
try {
    $calendars = $nylasClient->Calendars->Calendar->list(
        GRANT_ID_STRING
    );
    print_r($calendars);
} catch (GuzzleException) {}

//NOTE: Replace "GRANT_ID_STRING" with real grant id
```

### Drafts api
```php
try {
    $drafts = $nylasClient->Drafts->Draft->list(
        GRANT_ID_STRING
    );
    print_r($drafts);
} catch (GuzzleException) {}

//NOTE: Replace "GRANT_ID_STRING" with real grant id
```

## Launching the tests

1. Initialise composer dependency by  `composer install`
2. Add your Nylas App. info in tests/AbsCase.php
3. Launch the test with `composer run-script test`
4. Another way to run tests: ./tests/do.sh foo.php --filter fooMethod, see tests/do.sh

## Acknowledgements

This project is based on [lanlin/nylas-php](https://github.com/lanlin/nylas-php), which is licensed under the MIT License.
The original code has been modified and is also available under the MIT License.

## 📝License

This project is licensed under the MIT License. See the LICENSE file for details.

## Contact us

If you need additional assistance, drop us an email at [info@brainstream.tech](mailto:info@brainstream.tech).
