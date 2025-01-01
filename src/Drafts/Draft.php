<?php

namespace Nylas\Drafts;

use GuzzleHttp\Exception\GuzzleException;
use Nylas\Utilities\API;
use Nylas\Utilities\Helper;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validate as V;

/**
 * Draft api
 * @see https://developer.nylas.com/docs/api/v3/ecc/#tag--Drafts
 */
class Draft
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
     * Return all Drafts
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/drafts
     *
     * @param string $grantId
     * @param array $queryParams
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId, array $queryParams = []): array
    {
        V::doValidate(
            V::key('grantId', V::stringType()::notEmpty()),
            [
                'grantId' => $grantId
            ]
        );

        V::doValidate(
            Validation::searchFilterRules(),
            $queryParams
        );

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($queryParams)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['drafts']);
    }

    /**
     * Create a Draft
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/drafts
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

        V::doValidate(Validation::createDraftRules(), $params);

        // Simple message without attachment should be sent as form params
        if (empty($params['attachments'])) {

            return $this->options
                ->getSync()
                ->setPath($grantId)
                ->setFormParams($params)
                ->setHeaderParams($this->options->getAuthorizationHeader())
                ->post(API::LIST['drafts']);
        }

        $multipart = Helper::prepareMultipartRequestData($params, true);

        $headers = [];
        $hasMultipart = false;

        // attach multipart header data
        if (!empty($multipart)) {
            $hasMultipart = true;
            $headers['multipart'] = $multipart;
        }

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setHeaderParams(array_merge(
                $this->options->getAuthorizationHeader(),
                $headers
            ), $hasMultipart)
            ->post(API::LIST['drafts']);
    }

    /**
     * Return a Draft
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/drafts/-draft_id-
     *
     * @param string $grantId
     * @param string $draftId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $draftId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('draftId', V::stringType()::notEmpty())
            ),
            [
                'grantId' => $grantId,
                'draftId' => $draftId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $draftId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnDraft']);
    }

    /**
     * Update a draft
     * @see https://developer.nylas.com/docs/api/v3/ecc/#put-/v3/grants/-grant_id-/drafts/-draft_id-
     *
     * @param string $grantId
     * @param string $draftId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, string $draftId, array $params): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('draftId', V::stringType()::notEmpty())
            ),
            [
                'grantId' => $grantId,
                'draftId' => $draftId,
            ]
        );

        V::doValidate(Validation::updateDraftRules(), $params);

        // Simple message without attachment should be sent as form params
        if (empty($params['attachments'])) {

            return $this->options
                ->getSync()
                ->setPath($grantId, $draftId)
                ->setFormParams($params)
                ->setHeaderParams($this->options->getAuthorizationHeader())
                ->put(API::LIST['crudOnDraft']);
        }

        $multipart = Helper::prepareMultipartRequestData($params, true);

        $headers = [];
        $hasMultipart = false;

        // attach multipart header data
        if (!empty($multipart)) {
            $hasMultipart = true;
            $headers['multipart'] = $multipart;
        }
        return $this->options
            ->getSync()
            ->setPath($grantId, $draftId)
            ->setHeaderParams(array_merge(
                $this->options->getAuthorizationHeader(),
                $headers
            ), $hasMultipart)
            ->put(API::LIST['crudOnDraft']);
    }

    /**
     * Send a Draft
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/drafts/-draft_id-
     *
     * @param string $grantId
     * @param string $draftId
     * @return array
     * @throws GuzzleException
     */
    public function sendDraft(string $grantId, string $draftId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('draftId', V::stringType()::notEmpty())
            ),
            [
                'grantId' => $grantId,
                'draftId' => $draftId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $draftId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['crudOnDraft']);
    }

    /**
     * Delete a Draft
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/drafts/-draft_id-
     *
     * @param string $grantId
     * @param string $draftId
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId, string $draftId): array
    {
        V::doValidate(
            V::keySet(
                V::key('grantId', V::stringType()::notEmpty()),
                V::key('draftId', V::stringType()::notEmpty())
            ),
            [
                'grantId' => $grantId,
                'draftId' => $draftId,
            ]
        );

        return $this->options
            ->getSync()
            ->setPath($grantId, $draftId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnDraft']);
    }
}
