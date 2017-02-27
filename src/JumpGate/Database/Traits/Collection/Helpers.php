<?php

namespace JumpGate\Database\Traits\Collection;

trait Helpers
{
    /**
     * Explode a string and return a collection.
     *
     * @param string $delimiter
     * @param string $string
     * @param int    $limit
     *
     * @return $this
     */
    public static function explode($delimiter, $string, $limit = null)
    {
        $array = explode($delimiter, $string);

        if (! is_null($limit)) {
            $array = explode($delimiter, $string, $limit);
        }

        return new static($array);
    }

    /**
     * Creates a new Collection from a mixed variable.
     * Strings are assumed to be delimiter separated and are converted to arrays.
     *
     * @param mixed        $items     The values to include as items in the collection
     * @param string|array $delimiter (optional) for array parsing
     *
     * @return $this
     */
    public static function parseMixed($items, $delimiter = ',')
    {
        // Convert delimiter separated item strings
        // @example: foo,bar,baz => [foo, bar, baz]
        if (is_string($items)) {
            if (is_string($delimiter)) {
                $delimiters = str_split($delimiter);
            }
            $delimiters[] = $delimiter;

            // Removes any ',' that exist at the beginning or the end,
            // like in ',1,2,3,4,'
            $separator = '|';
            $items     = str_replace($delimiters, $separator, $items);
            $items     = explode($separator, trim($items, $separator));
        }

        // Put single element items in an array
        if (! is_array($items)) {
            $items = [$items];
        }

        // Clean up any empty values
        $items = array_filter($items, function ($input) {
            // Skip blank strings and nulls
            $isBlankString = is_string($input) && trim($input) == '';
            $isNullString  = is_null($input);

            return $isNullString || $isBlankString ? false : true;
        });

        // Return late static binding collection
        return new static($items);
    }
}
