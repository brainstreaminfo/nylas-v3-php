<?php

namespace Nylas\Resources;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validator as V;

class Resource
{
    public function __construct(private readonly Options $options)
    {
    }

    /**
     * Return all room resources
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/resources
     *
     * @param string $grantId
     * @param array $queryParam
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId, array $queryParam = []): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(
            V::keySet(
                V::keyOptional('limit', V::intType()::notEmpty()::max(200)),
                V::keyOptional('page_token', V::stringType()::notEmpty()),
            ),
            $queryParam
        );

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($queryParam)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['resource']);
    }
}
