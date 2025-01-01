<?php

declare(strict_types=1);

namespace Nylas\Utilities;

use function end;
use function key;
use function count;
use function reset;
use function is_int;
use function is_bool;

/**
 * Nylas Utils Helper
 */
class Helper
{
    /**
     * convert assoc array to multi
     *
     * @param array $arr
     * @return array|array[]
     */
    public static function arrayToMulti(array $arr): array
    {
        if (count($arr) === 0) {
            return $arr;
        }

        return self::isAssoc($arr) ? [$arr] : $arr;
    }

    /**
     * convert boolean to string value
     *
     * @param array $data
     * @return array
     */
    public static function boolToString(array $data): array
    {
        foreach ($data as $key => $val) {
            if (is_bool($val)) {
                $data[$key] = $val ? 'true' : 'false';
            }
        }

        return $data;
    }

    /**
     * check if an assoc array
     *
     * @param array $arr
     * @return bool
     */
    public static function isAssoc(array $arr): bool
    {
        if (count($arr) === 0) {
            return false;
        }

        if (!is_int(key($arr))) {
            return true;
        }

        end($arr);

        if (!is_int(key($arr))) {
            return true;
        }

        reset($arr);

        foreach ($arr as $key => $val) {
            if (!is_int($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare multipart request data for attachments
     *
     * @param array $params
     * @param bool $isDraft
     * @return array
     */
    public static function prepareMultipartRequestData(array $params, bool $isDraft = false): array
    {
        $multipart = [];
        foreach ($params['attachments'] as $i => $attachment) {
            $contents = $attachment['content'];

            //ref:adbrain changes done below as we are directly passing base64 content of file attached
            /*if (is_string($attachment['content']) && file_exists($attachment['content'])) {
                $contents = fopen($attachment['content'], 'rb');
            }*/

            $name = !empty($attachment['content_id']) ? $attachment['content_id'] : sprintf('file%s', $i);
            $multipart[] = [
                'Content-type'          => $attachment['content_type'],
                'name'                  => $name,
                'contents'              => $contents,
                'filename'              => $attachment['filename'],
                'content_id'            => $attachment['content_id'] ?? '',
                'content_disposition'   => $attachment['content_disposition'] ?? '',
                'is_inline'             => $attachment['is_inline'] ?? '',
            ];
        }

        // Remove attachments from main params
        unset($params['attachments']);

        // prepare message part(Contains all params excluding attachments)
        $multipart[] = [
            'name'      => $isDraft ? 'draft' : 'message',
            'contents'  => json_encode($params),
        ];

        return $multipart;
    }
}
