<?php

namespace bubbstore\Correios;

use bubbstore\Correios\Exceptions\CorreiosTrackingException;
use Goutte\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;

class CorreiosTracking
{
    private $trackingCode;

    const TRACKING_URL = 'http://www.linkcorreios.com.br';

    public function setTrackingCode($value)
    {
        return $this->trackingCode = $value;
    }

    public function getTrackingCode()
    {
        return $this->trackingCode;
    }

    public function __construct($trackingCode)
    {
        $this->setTrackingCode($trackingCode);
    }

    public function find()
    {
        try {
            $client = new Client;

            $crawler = $client->request('GET', self::TRACKING_URL.'/'.$this->getTrackingCode());
            $arr = [];

            $crawler->filter('ul.linha_status')->each(function ($node) use (&$arr) {
                $lastDate = null;

                $date = $node->filter('li')->eq(1)->text();
                $date = str_replace(['Data  : ', 'Data : ', ' | ', 'Hora:'], '', $date);
                $status = str_replace('Status: ', '', $node->filter('li')->eq(0)->text());
                $locale = str_replace(['Local: ', 'Origem: '], '', $node->filter('li')->eq(2)->text());

                $arr[$date] = [
                    'date' => $date,
                    'status' => $status,
                    'locale' => $locale,
                ];
            });

            $tracking = array_values($arr);

            $trackingObject = array_map(function ($key) {
                return [
                    'timestamp' => Carbon::createFromFormat('d/m/Y H:i', $key['date'])->timestamp,
                    'date' => Carbon::createFromFormat('d/m/Y H:i', $key['date'])->format('Y-m-d H:i'),
                    'locale' => rtrim($key['locale']),
                    'status' => $key['status'],
                    'forwarded' => isset($key['encaminhado']) ? $key['encaminhado'] : null,
                    'delivered' => $key['status'] == 'Entrega Efetuada' || $key['status'] == 'Objeto entregue ao destinatário'
                ];
            }, $tracking);

            if (!isset($trackingObject[0])) {
                throw new CorreiosTrackingException('Tracking code not found');
            }

            $firstTrackingObject = $trackingObject[0];

            return array_merge(
                ['code' => $this->getTrackingCode()],
                ['last_timestamp' => $firstTrackingObject['timestamp']],
                ['last_status' => $firstTrackingObject['status']],
                ['last_date' => $firstTrackingObject['date']],
                ['last_locale' => $firstTrackingObject['locale']],
                ['delivered' => $firstTrackingObject['delivered']],
                ['delivered_at' => ($firstTrackingObject['delivered']) ? $firstTrackingObject['date'] : null],
                ['tracking' => $trackingObject]
            );
        } catch (RequestException $e) {
            throw new CorreiosTrackingException($e->getMessage(), $e->getCode());
        }
    }
}
