<?php

namespace DreamCommerce\ShopAppstoreLib\Resource;

use DreamCommerce\ShopAppstoreLib\Resource;
use DreamCommerce\ShopAppstoreLib\Client\Exception\Exception;

/**
 * Resource Product
 *
 * @package DreamCommerce\ShopAppstoreLib\Resource
 * @link https://developers.shoper.pl/developers/api/resources/products
 */
class Bulk extends Resource
{
    protected $name = 'bulk';
    protected $limit = 25;

    public function put($id = null, $data = array())
    {
        $args = func_get_args();
        if(count($args) == 2){
            $args = $id;
        }else{
            $data = array_pop($args);
        }

        $response = '';
        $output = [];

        $chunks = array_chunk($data, $this->limit);

        try {
            foreach ($chunks as $chunk) {
                $response = $this->client->request($this, 'put', $args, $chunk);
                $responseData = $response['data'];

                foreach ($responseData['items'] as $item) { print_r($item);
                    if (!isset($item['id'])) {
                        $output[] = $item['body'];
                    } else {
                        $output[$item['id']] = $item['body'];
                    }
                }
            }

            return $output;
        } catch (Exception $ex) {
            $this->dispatchException($ex);
        }
    }

    public function post($data)
    {
        $args = func_get_args();
        if (count($args) == 1) {
            $args = null;
        } else {
            $data = array_pop($args);
        }

        $response = '';
        $output = [];

        $chunks = array_chunk($data, $this->limit);

        try {
            foreach ($chunks as $chunk) {
                $response = $this->client->request($this, 'post', $args, $chunk);
                $responseData = $response['data'];

                foreach ($responseData['items'] as $item) {
                    if (!isset($item['id'])) {
                        $output[] = $item['body'];
                    } else {
                        $output[$item['id']] = $item['body'];
                    }
                }
            }

            return $output;
        } catch (Exception $ex) {
            $this->dispatchException($ex);
        }
    }
}
