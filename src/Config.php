<?php

namespace PcbPlus\PcbDingtalk;

use ArrayAccess;

class Config implements ArrayAccess
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param mixed $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }
}
