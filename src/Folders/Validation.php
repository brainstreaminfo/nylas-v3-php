<?php

namespace Nylas\Folders;

use Nylas\Utilities\Validate as V;

class Validation
{
    public static function createFolderRules(): V
    {
        return V::keySet(
            V::key('name', V::stringType()::notEmpty()::length(1, 1024)),
            V::keyOptional('parent_id', V::stringType()),
            V::keyOptional('text_color', V::stringType()),
            V::keyOptional('background_color', V::stringType())
        );
    }
}
