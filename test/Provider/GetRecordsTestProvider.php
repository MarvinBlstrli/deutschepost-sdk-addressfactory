<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace PostDirekt\Sdk\AddressfactoryDirect\Test\Provider;

use PostDirekt\Sdk\AddressfactoryDirect\RequestBuilder\RequestBuilder;

/**
 * Class GetRecordsTestProvider
 *
 * @author Rico Sonntag <rico.sonntag@netresearch.de>
 * @link   https://www.netresearch.de/
 */
class GetRecordsTestProvider
{
    /**
     * Provide request and response for the test case.
     *
     * @return mixed[]
     */
    public static function processDataSuccess(): array
    {
        $requestBuilder = new RequestBuilder();

        $requestBuilder->setMetadata(1580213265);
        $requestBuilder->setPerson('Hans', 'Mustermann');
        $requestBuilder->setAddress('Deutschland', '53114', 'Bonn', 'Sträßchenweg', '10');
        $recordRequest = $requestBuilder->create();
        $singleResponseXml = \file_get_contents(__DIR__ . '/_files/getRecords/singleRecordResponse.xml');

        $recordRequests = [];

        $requestBuilder->setMetadata(1);
        $requestBuilder->setPerson('Hans', 'Mustermann');
        $requestBuilder->setAddress('Deutschland', '33739', 'Bielelfeld', 'Strusenweg', '36');
        $recordRequests[] = $requestBuilder->create();

        $requestBuilder->setMetadata(2);
        $requestBuilder->setPerson('Hans', 'Mustermann');
        $requestBuilder->setAddress('Deutschland', '53114', 'Bonn', 'Sträßchenweg', '10');
        $recordRequests[] = $requestBuilder->create();

        $multiResponseXml = \file_get_contents(__DIR__ . '/_files/getRecords/multiRecordResponse.xml');

        return [
            'single_record' => ['session-id', 'config-name', 'client-id', [$recordRequest], $singleResponseXml],
            'multi_record' => ['session-id', 'config-name', 'client-id', $recordRequests, $multiResponseXml],
        ];
    }

    public static function authenticationFailed(): array
    {
        $requestBuilder = new RequestBuilder();

        $requestBuilder->setAddress('Deutschland', '53114', 'Bonn', 'Sträßchenweg', '10');
        $recordRequest = $requestBuilder->create();

        $responseXml = \file_get_contents(__DIR__ . '/_files/errors/invalidCredentialsResponse.xml');

        return [
            'authentication_failed' => ['session-id', 'config-name', 'client-id', [$recordRequest], $responseXml],
        ];
    }

    /**
     * Provide request and response for the test case.
     *
     * @return mixed[]
     */
    public static function serverError(): array
    {
        $requestBuilder = new RequestBuilder();

        $requestBuilder->setAddress('Deutschland', '53114', 'Bonn', 'Sträßchenweg', '10');
        $recordRequest = $requestBuilder->create();

        $fault = new \SoapFault('soap:Server', 'Internal Server Error');

        return [
            'server_error' => ['session-id', 'config-name', 'client-id', [$recordRequest], $fault],
        ];
    }
}
