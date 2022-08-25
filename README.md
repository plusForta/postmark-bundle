# PlusForta PostmarkBundle

Das plusforta/postmark-bundle ist ein Bundle für das Symfony Framework zum Versenden von Template Mails über PostmarkApp 

## Installation

Zuerst muss das github-Repository zur composer.json hinzugefügt werden. 

**composer.json**
````json
"repositories": [
    ...
    {
      "type": "git",
      "url": "https://github.com/plusForta/postmark-bundle.git"
    }
  ],
````
```composer requrie plusforta/postmark-bundle```

## Konfiguration

Die Konfiguration kann eine config/packages/plusforta_postmark.yaml angelegt werden mit folgender Struktur:

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

Unter dev, prod und test können spezifische Konfigurationen für die Environments abgelegt werden.

### Pflichtangaben:

Angegeben werden muss die Konfiguration der Server:
`plusforta_postmark.servers`.
Der Wert entspricht dem Namen des Servers, sowie dessen API Key. 

### Optional:

#### defaults

Es ist möglich einen Default Wert für den Absender anzulegen (`plusforta_postmark.defaults.email` bzw. `plusforta_postmark.defaults.name`).

#### overrides

Es ist möglich den Empfänger permanent zu überschreiben (`plusforta_postmark.overrides.to.email`). Das ist sinnvoll für 
dev und staging Umgebung.

#### diable delivery 

Es ist möglich den Emailversand zu deaktiveren (`plusforta_postmark.disable_delivery`), dadurch werden keine Emails 
versendet und es findet auch keine Kommunikation mit dem Postmark Server statt. Das ist Sinnvoll für die Testumgebung.    


## Neue Email anlegen

Ein Beispiel für ein Mail Template ist `src/Mail/KFKundenanfrageTemplateMail.php`

1. Email erbt von `PlusForta\PostmarkBundle\Mail\BaseTemplateMail`
2. `SERVER_ID` muss angegeben werden. Dieser Server muss in der Konfiguration hinterlegt sein (`plusforta_postmark.servers`). 
3. `TEMPLATE_ID` oder `TEMPLATE_ALIAS` muss gesetzt sein. Die `TEMPLATE_ID` entspricht der ID bzw. dem ALIAS des Templates bei Postmark.
4. `$parameters` ist ein Array mit Parametern, die für das Template gesetzt werden können.
5. `getTemplate()` muss implementiert werden. Der Rückgabewert ist ein assoziatives Array. Ein json_encode auf das Array 
muss dem benötigte JSON für das Template bei Postmark entsprechen. 