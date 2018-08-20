# payfast-sdk-php
![MIT License](https://img.shields.io/github/license/fergusdixon/payfast-sdk-php.svg)
![Travis Build Status](https://travis-ci.com/fergusdixon/payfast-sdk-php.svg?branch=dev)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/d6158e0262a84d67927b771d12dd9d77)](https://www.codacy.com/project/fergusdixon101/payfast-sdk-php/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=fergusdixon/payfast-sdk-php&amp;utm_campaign=Badge_Grade_Dashboard)
[![StyleCI](https://github.styleci.io/repos/144857427/shield?branch=dev)](https://github.styleci.io/repos/144857427)
[![codecov](https://codecov.io/gh/fergusdixon/payfast-sdk-php/branch/dev/graph/badge.svg?token=h18LyV3ueg)](https://codecov.io/gh/fergusdixon/payfast-sdk-php)
![Packagist Version](https://img.shields.io/packagist/v/fergusdixon/payfast-sdk-php.svg)

A PHP handler for making requests to documented [PayFast](https://www.payfast.co.za) endpoints.

See their [docs](https://developers.payfast.co.za/documentation/)

## Features
- [x] CI tools
- [x] Handle [signature generation](https://developers.payfast.co.za/documentation/#api-signature-generation)
- [x] Generate Timestamp
- [x] Make user defined requests
- [ ] Support R0 verification charges
- [x] Error handling
- [ ] Built in functions
  - [ ] Ping
  - [ ] Transaction History
  - [ ] Query Card Transaction
  - [ ] Subscriptions
    - [ ] Charge Subscription
    - [ ] Fetch Subscription Info
    - [ ] Pause Subscription
    - [ ] Unpause Subscription
    - [ ] Cancel Subscription
    
## Usage
This PayFast SDK requires config variables passed to it. 

`merchantId` and `passPhrase` are mandatory, if not defined other fields will default to values shown below:

```php
$config = [
    'merchantId' => 'testId',               // Required
    'passPhrase' => 'testPhrase',           // Required
    'endpoint' => '//api.payfast.co.za',
    'port' => 443,
    'ssl' => true,
    'testing' => false,
];

$payfast = new PayFastSDK($config);
```

### Custom Requests
You can create a request to any endpoint in PayFast using `$payfast->request->custom($verb, $method, $options)`

For example, getting [Transaction History](https://developers.payfast.co.za/documentation/#transaction-history)
```php
$verb = 'GET';
$method = '/history';
$options = [
    'from' => '2018-02-01',
    'to' => '2018-03-04',
];
$response = $payfast->request->request($verb, $method, $options)
```

## Quirks

### Signature Generation
Payfast requires a MD5 hash of the alphabetised submitted variables, header and body variables, as well as the passphrase to ensure the request has not been tampered with.

### Testing
Payfast provides a [sandbox environment](https://sandbox.payfast.co.za/) to test integrations.

Test requests are sent to the normal endpoint, but append `?testing=true` to the URI.

## Acknowledgements
- Add yourself here!
