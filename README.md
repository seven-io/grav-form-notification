<img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" />

# [seven](https://www.seven.io) Form Notification Plugin

A [Grav CMS](https://github.com/getgrav/grav) plugin allowing you to send SMS as well as
making text-to-speech calls every time your Grav form gets submitted.

## Installation

There are two ways for installing the sms77 Form Notification plugin. The GPM (Grav
Package Manager) installation method enables you to quickly and easily install the plugin
with a simple terminal command, while the manual method enables you to do it via a zip
file. The former method is preferred as it is the most easy one.

### Via Grav Package Manager - preferred

The simplest way to install this plugin is via
the [Grav Package Manager (GPM)](https://learn.getgrav.org/advanced/grav-gpm) through your
system's command line (terminal). From the root directory of your Grav install type:

    bin/gpm install sms77-form-notification

This will install the sms77 Form Notification plugin into the `/user/plugins`
directory within your Grav installation. Its files can be found
under `/path/to/grav/user/plugins/sms77-form-notification`.

### Manually - for experienced users

To install this plugin, just download the zip version of
this [GitHub repository](https://github.com/omar-usman/grav-plugin-sms77-form-notification)
and unzip it under `/path/to/grav/user/plugins`. Then, rename the folder
to `sms77-form-notification`

All the plugin files should now be accessible under

    /path/to/grav/user/plugins/sms77-form-notification

> NOTICE: This plugin is a modular component for Grav which requires the plugins [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy
the `user/plugins/sms77-form-notification/sms77-form-notification.yaml`
to `user/config/plugins/sms77-form-notification.yaml` and edit it accordingly.

Have a look at the default configuration explaining the available options:

```yaml
api_key: your-seven/sms77-api-key # seven.io API key
enable_notification_user: true # whether to message the number specified in the "phone_field" or not
enable_notification: true # whether to message the number specified in "to" or not
enabled: true # whether the plugin is enabled or not
from: Grav # a custom sender name/number
message: 'You just received a new submission for your form named {{FORM_NAME}}.' # the message text
msg_type: sms # the message type: sms or voice
phone_field: mobile_phone # the users phone field
to: +491771783130 # the recipient number
user_response_msg: 'Thanks for your form submission.' # the message to send to the number specified in the "phone_field"
```

## Usage

You just need to create a [Grav form](https://learn.getgrav.org/forms). Now, whenever it
gets submitted, you should be receiving an SMS notification.

#### Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
