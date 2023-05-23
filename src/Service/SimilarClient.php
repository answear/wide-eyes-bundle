<?php

declare(strict_types=1);

namespace Answear\WideEyesBundle\Service;

use Answear\WideEyesBundle\Request\GetSimilarRequest;
use Answear\WideEyesBundle\Response\GetSimilarResponse;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class SimilarClient extends AbstractClient
{
    private const SEARCH_BY_ID_ENDPOINT = 'v4/SearchById';

    public function __construct(ConfigProvider $configProvider, ?ClientInterface $client = null)
    {
        parent::__construct(
            $configProvider,
            $client ?? new Client(
                [
                    'base_uri' => $configProvider->getSimilarApiUrl(),
                    'timeout' => $configProvider->getSimilarRequestTimeout(),
                ]
            )
        );
    }

    public function getSimilar(string $uid, string $countryCode, ?int $maxNumResults = null): GetSimilarResponse
    {
        return GetSimilarResponse::fromArray(
            $this->request(self::SEARCH_BY_ID_ENDPOINT, new GetSimilarRequest($uid, $countryCode, $maxNumResults)),
            $uid
        );
    }

    /**
     * @param string[] $uids
     *
     * @return array<string, GetSimilarResponse> // index by uids
     */
    public function getSimilarForMany(array $uids, string $countryCode, ?int $maxNumResults = null): array
    {
        $requests = [];
        foreach ($uids as $uid) {
            $requests[$uid] = new GetSimilarRequest($uid, $countryCode, $maxNumResults);
        }

        $results = $this->manyRequests(self::SEARCH_BY_ID_ENDPOINT, $requests);

        $responses = [];
        foreach ($results as $uid => $result) {
            $responses[$uid] = GetSimilarResponse::fromArray($result, $uid);
        }

        return $responses;
    }
}
