# SilverStripe Social Metadata

[![Version](http://img.shields.io/packagist/v/innoweb/silverstripe-social-share.svg?style=flat-square)](https://packagist.org/packages/innoweb/silverstripe-social-share)
[![License](http://img.shields.io/packagist/l/innoweb/silverstripe-social-share.svg?style=flat-square)](license.md)

## Overview

Adds sharing links and buttons for Facebook, Twitter, Instagram, Pinterest and Google+ as well as AddThis to the site.

## Requirements

* SilverStripe CMS ~3.2
* [social-metadata] (https://github.com/innoweb/silverstripe-social-metadata) ~1.0

## Installation

Install the module using composer:
```
composer require innoweb/silverstripe-social-share dev-master
```
or download or git clone the module into a ‘social-share’ directory in your webroot.

Then run dev/build.

## Configuration

The module adds a new tab to the SiteConfig in the CMS where the sharing options can be managed. 

To add the sharing links to your site, add the following include to your `Page.ss` template:

```
<% include PageShareLinks %>
```

This will activate sharing for the current page.

You can also activate sharing of the base URL of the site for all pages. For that, activate the following setting in your `config.yml`:

```
SocialShareConfigExtension:
  enable_base_share: true
``` 

You also have to add the following include to your `Page.ss` file:

```
<% include BaseShareLinks %>
```

### MultiSites support

The module supports the [multisites module] (https://github.com/silverstripe-australia/silverstripe-multisites) and by default adds the config options to the Sites.

If you want to manage the metadata globally, please add the following settings in your `config.yml`:

```
SocialMetaConfigExtension:
  multisites_enable_global_settings: true
``` 

This will add the fields to your SiteConfig instead of Site. 

## License

BSD 3-Clause License, see [License](license.md)
