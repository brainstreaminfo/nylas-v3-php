<?php

declare(strict_types=1);

namespace Nylas\Contacts;

use Nylas\Utilities\API;
use Nylas\Utilities\Options;
use Nylas\Utilities\Validate as V;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Nylas Contacts
 * @see https://developer.nylas.com/docs/api/v3/ecc/#tag--Contacts
 */
class Contact
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
     * Return all contacts in an end user's address book.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/contacts
     *
     * @param string $grantId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function list(string $grantId, array $params = []): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(Validation::searchContactRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setQuery($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['contacts']);
    }

    /**
     * Create a contact in an end user's address book.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#post-/v3/grants/-grant_id-/contacts
     *
     * @param string $grantId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function create(string $grantId, array $params): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(Validation::addContactRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->post(API::LIST['contacts']);
    }

    /**
     * Returns a contact by ID.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/contacts/-contact_id-
     *
     * @param string $grantId
     * @param string $contactId
     * @return array
     * @throws GuzzleException
     */
    public function find(string $grantId, string $contactId): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(V::stringType()::notEmpty(), $contactId);

        return $this->options
            ->getSync()
            ->setPath($grantId, $contactId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['crudOnContact']);
    }

    /**
     * Update a specific contact from the end user's address book.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#put-/v3/grants/-grant_id-/contacts/-contact_id-
     *
     * @param string $grantId
     * @param string $contactId
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    public function update(string $grantId, string $contactId, array $params): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(V::stringType()::notEmpty(), $contactId);

        V::doValidate(Validation::addContactRules(), $params);

        return $this->options
            ->getSync()
            ->setPath($grantId, $contactId)
            ->setFormParams($params)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->put(API::LIST['crudOnContact']);
    }

    /**
     * Delete a contact from the end user's address book.
     * @see https://developer.nylas.com/docs/api/v3/ecc/#delete-/v3/grants/-grant_id-/contacts/-contact_id-
     *
     * @param string $grantId
     * @param string $contactId
     * @return array
     * @throws GuzzleException
     */
    public function delete(string $grantId, string $contactId): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        V::doValidate(V::stringType()::notEmpty(), $contactId);

        return $this->options
            ->getSync()
            ->setPath($grantId, $contactId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->delete(API::LIST['crudOnContact']);
    }

    /**
     * Return a list of all Contact Groups associated with a grant.
     * NOTE: (Not supported for EWS)
     * @see https://developer.nylas.com/docs/api/v3/ecc/#get-/v3/grants/-grant_id-/contacts/groups
     *
     * @param string $grantId
     * @return array
     * @throws GuzzleException
     */
    public function contactGroups(string $grantId): array
    {
        V::doValidate(V::stringType()::notEmpty(), $grantId);

        return $this->options
            ->getSync()
            ->setPath($grantId)
            ->setHeaderParams($this->options->getAuthorizationHeader())
            ->get(API::LIST['contactsGroups']);
    }
}
