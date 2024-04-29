# PlusForta PostmarkBundle

The plusforta/postmark-bundle is a bundle for the Symfony framework for sending template mails via PostmarkApp

## Installation

```composer requrie plusforta/postmark-bundle```

## Versions

Right now this bundle is only used by the onlineantrag-php (ERP) project.  It is using version "^1.2"

| Version | Symfony Version | Branch |
|---------|:---------------:|--------|
| 1.0     |       5.4       | none   |
| 1.1     |       5.4       | none   |
| 1.2     |       6.3       | master |

## Configuration

The configuration can be created in `config/packages/plusforta_postmark.yaml` with the following structure:

```
plusforta_postmark:
  servers:
    - {name: 'plusforta.de', api_key: '%env(POSTMARK_KEY_PLUSFORTA)%'}
    - {name: 'test', api_key: '%env(POSTMARK_KEY_TEST)%'}
  defaults:
    from:
      email: "info@kautionsfrei.de"
      name: "PlusForta GmbH"
  overrides:
    to:
      email: jan.hentschel@plusforta.de
  disable_delivery: true

```

Specific configurations for the environments can be stored under dev, prod and test.

### Mandatory configuration information:

The server configuration must be specified:
`plusforta_postmark.servers`.
The value corresponds to the name of the server and its API key.

### Optional:

#### defaults

It is possible to create a default value for the sender (`plusforta_postmark.defaults.email`
or `plusforta_postmark.defaults.name`).

#### overrides

It is possible to permanently overwrite the recipient (`plusforta_postmark.overrides.to.email`). This is useful for
dev and staging environment.

#### disable delivery

It is possible to deactivate email delivery (`plusforta_postmark.disable_delivery`), which means that no emails are sent
and no communication
and there is no communication with the Postmark server. This is useful for the test environment.

## Create new email template

An example of a mail template is `src/Mail/KFKundenanfrageTemplateMail.php`.

1. email inherits from `PlusForta\PostmarkBundle\Mail\BaseTemplateMail`.
2. `SERVER_ID` must be specified. This server must be stored in the configuration (`plusforta_postmark.servers`).
3. `TEMPLATE_ID` or `TEMPLATE_ALIAS` must be set. The `TEMPLATE_ID` corresponds to the ID or ALIAS of the template in
   Postmark.
4. `$parameters` is an array with parameters that can be set for the template.
5. `getTemplate()` must be implemented. The return value is an associative array. A json_encode on the array
   must correspond to the required JSON for the template at Postmark. 