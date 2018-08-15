# payfast-sdk-php
A PHP handler for making requests to documented [Payfast](https://www.payfast.co.za) endpoints.

See their [docs](https://developers.payfast.co.za/documentation/)

## Features
- [ ] Handle [signature generation](https://developers.payfast.co.za/documentation/#api-signature-generation)
- [ ] Generate Timestamp
- [ ] Make user defined requests
- [ ] Support R0 verification charges
- [ ] Error handling
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
    
## Quirks

### Signature Generation
Payfast requires a MD5 hash of the alphabetised submitted variables, header and body variables, as well as the passphrase to ensure the request has not been tampered with.

### Testing
Payfast provides a [sandbox environment](https://sandbox.payfast.co.za/) to test integrations.

Test requests are send to the normal endpoint, but append `?testing=true` to the URI.
