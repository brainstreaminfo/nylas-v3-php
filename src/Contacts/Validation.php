<?php

declare(strict_types=1);

namespace Nylas\Contacts;

use Nylas\Utilities\Validate as V;

/**
 * Nylas Contacts
 */
class Validation
{
    /**
     * search contact rules
     *
     * @return V
     */
    public static function searchContactRules(): V
    {
        return V::keySet(
            V::keyOptional('limit', V::intType()::length(1, 200)),
            V::keyOptional('page_token', V::stringType()),
            V::keyOptional('select', V::stringType()),
            V::keyOptional('email', V::email()),
            V::keyOptional('phone_number', V::stringType()),
            V::keyOptional('source', V::in(['address_book', 'domain', 'inbox'])),
            V::keyOptional('group', V::stringType()),
            V::keyOptional('recurse', V::stringType())
        );
    }

    /**
     * rules for add contact
     *
     * @return V
     */
    public static function addContactRules(): V
    {
        return V::keySet(
            V::keyOptional('birthday', V::stringType()),
            V::keyOptional('company_name', V::stringType()),
            self::contactEmailsRules(),
            V::key('given_name', V::stringType()::notEmpty()),
            V::keyOptional('groups', V::arrayVal()->each(
                V::keySet(
                    V::key('id', V::stringType()::notEmpty())
                )
            )),
            V::keyOptional('im_addresses', V::arrayVal()->each(
                V::keySet(
                    V::key('im_address', V::stringType()::notEmpty()),
                    V::keyOptional('type', V::stringType())
                )
            )),
            self::contactImAddressRules(),
            V::keyOptional('job_title', V::stringType()),
            V::keyOptional('manager_name', V::stringType()),
            V::keyOptional('middle_name', V::stringType()),
            V::keyOptional('nickname', V::stringType()),
            V::keyOptional('notes', V::stringType()),
            V::keyOptional('office_location', V::stringType()),
            self::contactPhoneNumberRules(), // phone_numbers
            self::contactPhysicalAddressRules(), // physical_addresses
            V::keyOptional('source', V::in(['address_book', 'domain', 'inbox'])),
            V::keyOptional('suffix', V::stringType()),
            V::keyOptional('surname', V::stringType()),
            self::contactWebPageRules() // web_pages
        );
    }

    /**
     * emails rules
     *
     * @return V
     */
    private static function contactEmailsRules(): V
    {
        return V::keyOptional('emails', V::arrayVal()->each(
            V::keySet(
                V::key('type', V::in(['work', 'home', 'other'])),
                V::key('email', V::stringType()::notEmpty()::length(null, 255))
            )
        ));
    }

    /**
     * web page rules
     *
     * @return V
     */
    private static function contactWebPageRules(): V
    {
        return V::keyOptional('web_pages', V::arrayVal()->each(
            V::keySet(
                V::key('url', V::stringType()::notEmpty()),   // a free-form string
                V::key('type', V::in(['work', 'home', 'other']))
            )
        ));
    }

    /**
     * im addresses rules
     *
     * @return V
     */
    private static function contactImAddressRules(): V
    {
        return V::keyOptional('im_addresses', V::arrayVal()->each(
            V::keySet(
                V::key('im_address', V::stringType()::notEmpty()),  // a free-form string
                V::keyOptional('type', V::stringType())
            )
        ));
    }

    /**
     * phone number rules
     *
     * @return V
     */
    private static function contactPhoneNumberRules(): V
    {
        return V::keyOptional('phone_numbers', V::arrayVal()->each(
            V::keySet(
                V::key('number', V::stringType()::notEmpty()), // a free-form string
                V::keyOptional('type', V::in(['work', 'home', 'other']))
            )
        ));
    }

    /**
     * physical address rules
     *
     * @return V
     */
    private static function contactPhysicalAddressRules(): V
    {
        return V::keyOptional('physical_addresses', V::arrayVal()->each(
            V::keySet(
                V::keyOptional('street_address', V::stringType()::notEmpty()),
                V::keyOptional('postal_code', V::stringType()::notEmpty()),
                V::keyOptional('state', V::stringType()::notEmpty()),
                V::keyOptional('country', V::stringType()::notEmpty()),
                V::keyOptional('city', V::stringType()::notEmpty()),
                V::keyOptional('type', V::in(['work', 'home', 'other']))
            )
        ));
    }
}
