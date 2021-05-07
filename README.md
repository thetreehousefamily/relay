<h1 align="center">
<img src="https://storage.googleapis.com/thetreehouse-family.appspot.com/relay/assets/relay-on-white.png" width="450">
</h1>

<p align="center">
<a href="https://packagist.org/packages/thetreehouse/relay"><img src="https://img.shields.io/packagist/v/thetreehouse/relay.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://github.com/thetreehousefamily/relay/actions?query=workflow%3Arun-tests+branch%3Amaster"><img src="https://img.shields.io/github/workflow/status/thetreehousefamily/relay/run-tests?label=tests" alt="GitHub Tests Action Status"></a>
<a href='https://github.com/thetreehousefamily/relay/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster'><img src="https://img.shields.io/github/workflow/status/thetreehousefamily/relay/Check%20&%20fix%20styling?label=code%20style" alt="GitHub Code Style Action Status"></a>
<a href="https://packagist.org/packages/thetreehouse/relay"><img src="https://img.shields.io/packagist/dt/thetreehouse/relay.svg?style=flat-square" alt="Total Downloads"></a>
</p>

<p align="center">
Painlessly sync your contacts and organizations between your Laravel application and the rest of your operational suite.
</p>

<hr>

Relay helps you to bootstrap your internal integrations by painlessly, **bidirectionally**, syncing the contacts and organizational accounts between your Laravel application and the rest of your operational suite.

> Note: This is the core Relay repository. If you're looking to install a particular driver, checkout the [Drivers](#) section.

## Contents
- [What Problem does Relay Solve?](#what-problem-does-relay-solve?)
- [Requirements](#requirements)
- [Installation](#installation)
    - [Driver Installation](#driver-installation)
- [General Usage](#general-usage)
- [Testing](#testing)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Security Vulnerabilities](#security-vulnerabilities)
- [Credits](#credits)
- [License](#License)



## What Problem does Relay Solve?

In the early stages (or frankly any stage) of a startup, the development team is often tasked with integrating the platform or application with different operational tools. Think CRMs, marketing automation suites, payment gateways etc.

Relay lets you, as a developer, focus on building the actual product instead of integrating time consuming, (and often poorly documented) third party APIs. By abstracting these service's APIs into plug-and-play driver packages, Relay simply observes the relevant Eloquent models of your application and tasks these drivers with updating the relevant service.

## Requirements

Relay requires PHP `^8.0` or `^7.4`, and Laravel `^8.0`.

## Installation

// TODO: Installation instructions, including locking the core Relay package

### Driver Installation

// TODO: Driver Installation

## General Usage

// TODO: General Usage, including reference to see each package's specific usage instructions

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Frank Dixon](https://github.com/frankieeedeee)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
