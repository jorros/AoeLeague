<?php

class DBRef implements \MongoDB\BSON\Serializable, ArrayAccess
{
    public $ref;
    public $id;
    public $db;

    public static function create($collection, $id) {
        $dbref = new DBRef();
        $dbref->ref = $collection;
        $dbref->id = $id;
        $dbref->db = "League";

        return $dbref;
    }

    /**
     * Provides an array or document to serialize as BSON
     * Called during serialization of the object to BSON. The method must return an array or stdClass.
     * Root documents (e.g. a MongoDB\BSON\Serializable passed to MongoDB\BSON\fromPHP()) will always be serialized as a BSON document.
     * For field values, associative arrays and stdClass instances will be serialized as a BSON document and sequential arrays (i.e. sequential, numeric indexes starting at 0) will be serialized as a BSON array.
     * @link http://php.net/manual/en/mongodb-bson-serializable.bsonserialize.php
     * @return array|object An array or stdClass to be serialized as a BSON array or document.
     */
    public function bsonSerialize()
    {
        $serialized = array(
            '$ref' => $this->ref,
            '$id' => $this->id,
            '$db' => $this->db
        );

        return $serialized;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return($offset == '$id' || $offset == '$ref' || $offset == '$db');
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        switch($offset) {
            case '$id':
                return $this->id;

            case '$ref':
                return $this->ref;

            case '$db':
                return $this->db;

            default:
                return null;
        }
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        switch($offset) {
            case '$id':
                $this->id = $value;
                break;

            case '$ref':
                $this->ref = $value;
                break;

            case '$db':
                $this->db = $value;
                break;
        }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        switch($offset) {
            case '$id':
                $this->id = null;
                break;

            case '$ref':
                $this->ref = null;
                break;

            case '$db':
                $this->db = null;
                break;
        }
    }
}