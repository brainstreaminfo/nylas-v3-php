<?php

declare(strict_types=1);

namespace Nylas\Folders;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validate as V;

/**
 * Folder Api
 * @see https://developer.nylas.com/docs/api/v3/ecc/#tag--Folders
 */
class Folder
{
    /**
     * Manage constructor.
     *
     * @param Options $options
     */
    private $options;

    public function __construct(Options $options)
    {
        $this->options = $options;
    }

    /**
     * Return all folders
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/folders
     *
     * @param string $grantId
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['folders']);
    }

    /**
     * Create a Folder
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/folders
     *
     * @param string $grantId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(string $grantId, array $params): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(Validation::createFolderRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['createFolder']);
    }

    /**
     * Return a Folder
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/folders/-folder_id-
     *
     * @param string $grantId
     * @param string $folderId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $folderId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('folderId', V::stringType()::notEmpty())
            ),
            [
                'grantId'   => $grantId,
                'folderId'  => $folderId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $folderId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnFolder']);
    }

    /**
     * Update a folder
     * @see https://developer.nylas.com/docs/api/v3/ecc/#put-/v3/grants/-grant_id-/folders/-folder_id-
     *
     * @param string $grantId
     * @param string $folderId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, string $folderId, array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('folderId', V::stringType()::notEmpty())
            ),
            [
                'grantId'   => $grantId,
                'folderId'  => $folderId,
            ]
        );

        V::doValidate(Validation::createFolderRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($grantId, $folderId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['crudOnFolder']);
    }

    /**
     * Delete a Folder
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/folders/-folder_id-
     *
     * @param string $grantId
     * @param string $folderId
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId, string $folderId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('folderId', V::stringType()::notEmpty())
            ),
            [
                'grantId'   => $grantId,
                'folderId'  => $folderId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $folderId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnFolder']);
    }
}
