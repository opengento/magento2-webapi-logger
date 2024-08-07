# Webapi Logger for Magento 2

[![Latest Stable Version](https://img.shields.io/packagist/v/opengento/module-webapi-logger.svg?style=flat-square)](https://packagist.org/packages/opengento/module-webapi-logger)
[![License: MIT](https://img.shields.io/github/license/opengento/magento2-webapi-logger.svg?style=flat-square)](./LICENSE)
[![Packagist](https://img.shields.io/packagist/dt/opengento/module-webapi-logger.svg?style=flat-square)](https://packagist.org/packages/opengento/module-webapi-logger/stats)
[![Packagist](https://img.shields.io/packagist/dm/opengento/module-webapi-logger.svg?style=flat-square)](https://packagist.org/packages/opengento/module-webapi-logger/stats)

This module allows you to analyze all the webapi rest done call toward your Magento.

- [Fork](#fork)
- [Setup](#setup)
    - [Composer installation](#composer-installation)
    - [Setup the module](#setup-the-module)
- [Features](#features)
- [Settings](#settings)
- [Documentation](#documentation)
- [Support](#support)
- [Authors](#authors)
- [License](#license)

## Fork

This is a forked version from the following repository: https://github.com/ghostunicorns/module-webapi-logs.  
It also contains changes from the following fork: https://github.com/magentix/module-webapi-logs.

As the original repository does not seems to be maintained anymore, we offer an improved and optimized version here.

## Setup

Magento 2 Open Source or Commerce edition is required.

###  Composer installation

Run the following composer command:

```
composer require opengento/module-webapi-logger
```

### Setup the module

Run the following magento command:

```
bin/magento setup:upgrade
```

**If you are in production mode, do not forget to recompile and redeploy the static resources.**

## Features

## Settings

The configuration for this module is available in `Stores > Configuration > System > Webapi Logs`.

<img src="https://github.com/opengento/magento2-webapi-logger/blob/main/screenshots/screen1.png" />

## Documentation

Go to `Reports > Webapi Logs > Show Logs`

<img src="https://github.com/opengento/magento2-webapi-logger/blob/main/screenshots/screen2.png" />

You can select an entry to see more details about the request and the response

<img src="https://github.com/opengento/magento2-webapi-logger/blob/main/screenshots/screen3.png" />

If you disable the Secret Mode this module will logs everything passes in the webapi calls (tokens and passwords too!), then remember to clean logs by clicking the `Delete All Logs` button:

<img src="https://github.com/opengento/magento2-webapi-logger/blob/main/screenshots/screen4.png" />

It is now possible to log the webapi request and response to filesystem or any handler that follow the PSR Log.  
You can register your handlers to `Opengento\WebapiLogger\Logger`. The module use the `debug` level to log the request and response.

## Support

Raise a new [request](https://github.com/opengento/magento2-webapi-logger/issues) to the issue tracker.

## Authors

- **GhostUnicorns** - *Original Work* - [www.ghostunicorns.com](https://www.ghostunicorns.com/)
- **Contributors** - *Contributor* - [![GitHub contributors](https://img.shields.io/github/contributors/opengento/magento2-webapi-logger.svg?style=flat-square)](https://github.com/opengento/magento2-webapi-logger/graphs/contributors)
- **Opengento Community** - *Maintainer* - [![Twitter Follow](https://img.shields.io/twitter/follow/opengento.svg?style=social)](https://twitter.com/opengento)

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) details.

***That's all folks!***
