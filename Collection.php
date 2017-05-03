<?php

class Collection implements IteratorAggregate, ArrayAccess {

    private $items;

    public function __construct(array $items = NULL) {
        $this->items = $items;
    }

    /**
     * Sets $value in $array where $key is the recursive Path inside $array
     *
     * @param string $path
     * @param mixed $value
     */
    public function set($path, $value) {
        if ($path == NULL) {
            $this->items = $value;
        } else {
            $this->_set( $this->items, $path, $value);
        }
    }

    /**
     * Push $value in $array where $key is the recursive Path inside $array
     * if the key has element just add the new value 
     *
     * @param string $path
     * @param mixed $value
     */
    public function push($path, $value) {
        if ($path == NULL) {
            $this->items = $value;
        } else {
            $this->_set( $this->items, $path, $value, true);
        }
    }

    private function _set(&$array, $path, $value, $push = false) {
        if (!$this->isAcceptedArrayType($array)) {
            $array = array();
        }

        $parts = explode('.', $path);


        if (count($parts) > 1) {
            $part = array_shift($parts);

            if (!$this->isAcceptedArrayType($array)) {
                $array = array();
            }

            if (!array_key_exists($part, $array)) {
                $array[$part] = null;
            }
            
            $array[$part] = $this->_set($array[$part], join('.', $parts), $value);
        } else {
            // if $push true dont remove the old value
            if (isset($array[$path]) && $push == true)
                // if the old value is array just push the new value to it
                // if not so add the new value and the old as array
                if (is_array($array[$path]))
                    array_push($array[$path], $value);
                else 
                    $array[$path] = [$array[$path], $value];
            else 
                // if $push is false just puth the new value in stat of the old
                $array[$path] = $value;
        }
        
        return $array;
    }

    /**
     * get the value in the $path
     * @param string $path
     * @param mixed $default
     * @return mixed
     */
    public function get($path, $default = NULL) {
        $has = $this->_has(explode('.', $path), $this->items);
        if ($has[0]) {

            return is_array($has[1]) ? new Collection($has[1]) : $has[1];
        } else {
            return $default;
        }

        /*
          return $this->getValue(
          explode('.', $path),
          $this->items,
          $default
          );
         */
    }

    /**
     * get the value in the $path and remove it from the array
     * @param string $path
     * @param mixed $default
     * @return mixed
     */
    public function pop($path, $default = NULL) {
        $value = $this->get($path, $default);
        $this->clear($path);
        return $value;
    }

    private function &getValue(array $indexs, array $value, $default = NULL) {
        $key = array_shift($indexs);
        if (empty($indexs)) {
            if (!array_key_exists($key, $value)) {
                return $default;
            }

            if (is_array($value[$key])) {
                return $value[$key];
            } else {
                return $value[$key];
            }
        } else {
            return $this->getValue($indexs, $value[$key], $default);
        }
    }
     
    
    /**
     * Determines if $path exists in $array
     *
     * @param string $path
     * @return array
     */
    public function has($path) {
        $has = $this->_has(explode('.', $path), $this->items);
        return $has[0];
    }

    private function _has($keys, $value) {
        $key = array_shift($keys);
        if (empty($keys)) {
            $result = array(FALSE);
            if (array_key_exists($key, $value)) {
                $result = array(TRUE, $value[$key]);
            }
            return $result;
        } else {
            return $this->_has($keys, $value[$key]);
        }
    }

    public function toArray() {
        return $this->items;
    }

    /**
     * get lists array of $key and $value
     * 
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public function lists($key, $value) {
        $result = array();
        foreach ($this->items as $item) {
            $result[$item[$key]] = $item[$value];
        }
        return new Collection($result);
    }

    /**
     * get extract of $key 
     * 
     * @param string $key
     * @return array
     */
    public function extract($key) {
        $result = array();
        foreach ($this->items as $item) {
            $result[] = $item[$key];
        }
        return new Collection($result);
    }

    public function join($glue) {
        return implode($glue, $this->items);
    }

    /**
     * get max element in array by $key
     * 
     * @param string $key
     * @return mixed
     */
    public function max($key = FALSE) {
        if ($key) {
            return $this->extract($key)->max();
        } else {
            return max($this->items);
        }
    }

    
    /**
     * clear path form array
     * 
     * @param string $path
     * @return array
     */
    public function clear($path) {
        $this->clearKey(
                $this->items,
                explode('.', $path));
    }

    /**
     * @param array $array
     * @param array $path
     * @return array
     */
    private function clearKey(array &$array, array $path) {
        if (count($path)) {
            $key = array_shift($path);
            if (array_key_exists($key, $array)) {
                if (empty($path)) {
                    unset($array[$key]);
                    
                } else {
                    $this->clearKey($array[$key], $path);
                }
            }
        }
    }
    
    //=============================================
    //=============================================
    /**
    * implements IteratorAggregate, ArrayAccess
    * to use the object as array
    */
    public function offsetExists($offset) {
        $this->has($key);
    }

    public function offsetGet($offset) {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value) {
        return $this->set($offset, $value);
    }

    public function offsetUnset($offset) {
        if ($this->has($offset))
            unset($this->items[$offset]);
    }

    public function getIterator() {
        return new ArrayIterator($this->items);
    }

    
    /**
     * Returns true when $array is an array or an ArrayObject
     *
     * @param mixed $array
     * @return bool
     */
    private function isAcceptedArrayType($array) {
        return is_array($array) || $array instanceof ArrayObject;
    }
}
