<?php

namespace Modules\Model;

use Modules\Decorator\IDecorator;
use Modules\Exception\Exceptions\ModelException;

interface IModel
{

    const DEFAULT_PRIMARY_KEY = 'id';

    /**
     * @return string Name of primary ID value
     */
    public function primary();

    /**
     * Retrieves value of primary key for current entity
     *
     * @param mixed $default value that will be returned in case that attribute does not exists
     * @return mixed
     */
    public function primaryValue($default = null);

    /**
     * @return string Table/collection name
     */
    public function table();

    /**
     *
     * Validates does model (entity) exists in storage
     *
     * @return bool
     */
    public function exists();

    /**
     *
     * List of all available fields (columns)
     *
     * @return array
     */
    public function fields();

    /**
     *
     * Find and populate model with data using primary key and given value
     *
     * @param $primary
     * @return mixed
     */
    public function find($primary);


    /**
     *
     * Find and populate model with data using criteria of array
     *
     * @param array $criteria
     * @return $this|IModel|null
     */
    public function findWhere(array $criteria);

    /**
     *
     * Get bulk of field values
     *
     * @param array $attributes
     * @return array
     */
    public function getAttributes(array $attributes);


    /**
     *
     * Get specific field value
     *
     * @param $name
     * @param $default
     * @return mixed|null
     */
    public function getAttribute($name, $default = null);

    /**
     *
     * Set specific field value
     *
     * @param string $name Field name
     * @param mixed $value Value of field
     * @return $this|Model
     * @throws ModelException
     */
    public function setAttribute($name, $value);

    /**
     *
     * Bulk set of model values
     *
     * @param array $data
     * @return $this|Model
     */
    public function setAttributes(array $data);

    /**
     * @return bool
     */
    public function save();

    /**
     * Reset all changes to original values
     */
    public function reset();

    /**
     * @return bool Is model dirty, should it be updated (saved)
     */
    public function dirty();

    /**
     *
     * Removes model (entity) from storage
     *
     * @return bool
     */
    public function delete();

    /**
     * @param string $decoratorClassName Class name of wanted decorator
     * @param array $decoratorParameters Parameters that will be passed to decorator constructor
     * @return IDecorator
     */
    public function decorate($decoratorClassName, array $decoratorParameters = []);

    /**
     *
     * Model data with type of array
     *
     * @return array
     */
    public function toArray();
}