<?php

namespace Tlapnet\Report\HeapBox;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Utils\Suggestions;

class Metadata
{

    /** @var array */
    private $data = [];

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = NULL)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (func_num_args() < 2) {
            $hint = Suggestions::getSuggestion($this->data, $key);
            throw new InvalidArgumentException("Unknown key '$key'" . ($hint ? ", did you mean '$hint'?" : '.'));
        }

        return $default;
    }

}
