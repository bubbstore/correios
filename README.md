# Correios

Biblioteca que faz cálculo de frete, rastreamento de objetos e consulta de CEP diretamente do Webservice dos Correios.

[![StyleCI](https://styleci.io/repos/118616249/shield?branch=master)](https://styleci.io/repos/118616249)

## Instalação via composer

```bash
$ composer require bubbstore/correios
```

## Consulta CEP

```php
<?php

use bubbstore\Correios\Zipcode;

$zipcode = new Zipcode('14940000');
echo '<pre>' . json_encode($zipcode->find()) . '</pre>';

```

Resultado esperado:

```
[
    'zipcode' => '14940000',
    'street' => [],
    'complement' => [],
    'district' => [],
    'city' => 'Ibitinga',
    'uf' => 'SP',
]
```

## Cálculo de frete

```php
<?php

use bubbstore\Correios\CorreiosQuote;
use bubbstore\Correios\Exceptions\CorreiosQuoteException;

try
{
	$quote = new CorreiosQuote();

	$items = [
		[16, 16, 16, .3, 2], // largura, altura, comprimento, peso e quantidade
		[16, 16, 16, .3, 2], // largura, altura, comprimento, peso e quantidade
	];

	$result = $quote->setOrigin('14940000')
					->setDestination('14900000')
					->setServices(['4014', '4510'])
					->setItems($items)
					->setCompanyCode('16181271')
					->setPassword('11570480')
					->calculate();

	echo '<pre>' . json_encode($result) . '</pre>';

} catch ( CorreiosQuoteException $e )
{
	echo $e->getMessage();
}
```

Resultado esperado:

```
[
    [
        'name' => 'Sedex',
        'code' => 40010,
        'price' => 51,
        'deadline' => 4,
        'error' => [],
    ],
    [
        'name' => 'PAC',
        'code' => 41106,
        'price' => 22.5,
        'deadline' => 9,
        'error' => [],
    ],
]
```

## Rastreamento de objetos

```php
<?php

use bubbstore\Correios\CorreiosTracking;
use bubbstore\Correios\Exceptions\CorreiosTrackingException;

try {

	$tracking = new CorreiosTracking('PO548836895BR');
	$result = $tracking->find();

	exit(var_dump($result));

} catch (CorreiosTrackingException $e) {
	echo $e->getMessage();
}

```

O resultado esperado será:

```json
{
    "code": "PO548836895BR",
    "last_timestamp": 1502126880,
    "last_status": "Em trânsito para CTCE RIBEIRAO PRETO - RIBEIRAO PRETO/SP",
    "last_date": "2017-08-07 14:28",
    "last_locale": null,
    "delivered": false,
    "delivered_at": null,
    "tracking": [
        {
            "timestamp": 1502126880,
            "date": "2017-08-07 14:28",
            "place": "CTE VILA MARIA - SAO PAULO/SP Objeto encaminhado",
            "status": "Em trânsito para CTCE RIBEIRAO PRETO - RIBEIRAO PRETO/SP",
            "forwarded": null,
            "delivered": false
        },
        {
            "timestamp": 1502109900,
            "date": "2017-08-07 09:45",
            "place": "AGF JARDIM MARILIA - SAO PAULO/SP Objeto encaminhado",
            "status": "Em trânsito para CTE VILA MARIA - SAO PAULO/SP",
            "forwarded": null,
            "delivered": false
        },
        {
            "timestamp": 1501868640,
            "date": "2017-08-04 14:44",
            "place": "AGF JARDIM MARILIA - SAO PAULO/SP",
            "status": "Objeto postado",
            "forwarded": null,
            "delivered": false
        }
    ]
}
```

## Change log

Consulte [CHANGELOG](.github/CHANGELOG.md) para obter mais informações sobre o que mudou recentemente.

## Contribuindo

Consulte [CONTRIBUTING](.github/CONTRIBUTING.md) para obter mais detalhes.

## Segurança

Se você descobrir quaisquer problemas relacionados à segurança, envie um e-mail para contato@bubbstore.com.br em vez de usar as issues.