<?php

namespace Modules\Model;

use Modules\Database\Database;
use Modules\Decorator\Decorator;
use Modules\Decorator\IDecorator;
use Modules\Exception\Exceptions\ModelException;
use Modules\Singleton\Singleton;


/**
 *
 * Extend this class to ensure that child class is model that can be stored, fetched and removed from database
 *
 */
abstract class Model implements IModel
{

    use Singleton;

    /** @var array */
    protected $_attributes = [];

    /** @var array */
    protected $_updatedAttributes = [];

    /** @var array Properties that will be excluded in toArray() method */
    protected $_hidden = [];

    /** @var bool */
    protected $_isDirty;

    /** @var Database */
    protected $_db;

    /** @var IDecorator[] */
    protected $_instantiatedDecorators = [];

    /**
     * @param array $attributes Initial model values
     * @param bool $isDirty
     */
    private function __construct(array $attributes = [], $isDirty = null)
    {

        $this->_db = Database::getSharedInstance();

        if (false === $isDirty) {

            $this->_attributes = $attributes;

            $this->_isDirty = false;

            return;
        }

        $this->setAttributes($attributes);
    }

    /**
     *
     * Try setting parameter to model attributes
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {

        $this->setAttribute($name, $value);
    }

    /**
     *
     * Try fetching required parameter from model attributes
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {

        return $this->getAttribute($name);
    }

    /**
     *
     * Retrieve current model primary key identifier
     *
     * @return string Name of primary key field
     */
    public function primary()
    {

        return IModel::DEFAULT_PRIMARY_KEY;
    }

    /**
     * Retrieves value of primary key for current entity
     *
     * @param mixed $default value that will be returned in case that attribute does not exists
     * @return mixed
     */
    public function primaryValue($default = null)
    {

        return $this->getAttribute($this->primary(), $default);
    }

    /**
     *
     * Will retrieve single entity from database
     *
     * @param $primaryValue
     * @return IModel
     */
    public function find($primaryValue)
    {

        $results = $this->_db->fetch("SELECT * FROM `{$this->table()}` WHERE `{$this->primary()}` = ?;", [$primaryValue]);

        if (false === is_array($results)) {

            return static::getNewInstance();
        }

        return static::getNewInstanceArgs([$results, false]);
    }

    /**
     *
     * Retrieve single entity with given criteria
     *
     * @param array $criteria
     * @return $this|IModel|null
     */
    public function findWhere(array $criteria)
    {
        $results = $this->_db->fetch($this->_buildCriteriaSelectQuery($this->table(), $criteria), array_values($criteria));

        if (false === is_array($results)) {

            return static::getNewInstance();
        }

        return static::getNewInstanceArgs([$results, false]);
    }

    /**
     *
     * Save current entity into database
     *
     * @return int
     */
    public function save()
    {

        if (false === $this->_isDirty) {

            return false;
        }

        $attributes = array_merge($this->_attributes, $this->_updatedAttributes);

        $query = $this->_buildSaveQuery($this->table(), $this->primary(), $attributes);

        $updateBindings = $attributes;

        if (isset($updateBindings[$this->primary()])) {

            unset($updateBindings[$this->primary()]);
        }

        $id = $this->_db->store($query, array_merge(array_values($attributes), array_values($updateBindings)));

        if ($id) {

            $this->setAttribute($this->primary(), $id);

            $this->_attributes = array_merge($this->_attributes, $this->_updatedAttributes);

            $this->reset();
        }

        return false === empty($id);
    }

    /**
     *
     * Removes current entity from database
     *
     * @return bool
     * @throws ModelException
     */
    public function delete()
    {

        if (empty($this->primary())) {

            throw new ModelException(ModelException::ERROR_MISSING_PRIMARY, 'Primary key is required for deleting.');
        }

        $id = $this->primaryValue();

        if (null === $id) {

            throw new ModelException(ModelException::ERROR_MISSING_PRIMARY, 'Primary key value is required for deleting.');
        }

        $deleted = (bool)$this->_db->delete("DELETE FROM `{$this->table()}` WHERE `{$this->primary()}` = ?;", [$id]);

        return $this->_isDirty = $deleted;
    }

    /**
     *
     * Bulk set of model values
     *
     * @param array $data
     * @return $this|Model
     */
    public function setAttributes(array $data)
    {

        foreach ($data as $name => $value) {

            if ($this->getAttribute($name) !== $value) {

                $this->setAttribute($name, $value);
            }
        }

        return $this;
    }

    /**
     *
     * Set specific field value
     *
     * @param string $name Field name
     * @param mixed $value Value of field
     * @return $this|Model
     * @throws ModelException
     */
    public function setAttribute($name, $value)
    {

        if (false === in_array($name, $this->fields())) {

            throw new ModelException(ModelException::ERROR_INVALID_FIELD, $name);
        }

        if ($value instanceof Model) {

            $value = $value->primaryValue();
        }

        $this->_isDirty = true;

        $this->_updatedAttributes[$name] = $value;

        return $this;
    }

    /**
     *
     * Get specific field value
     *
     * @param $name
     * @param $default
     * @return mixed|null
     */
    public function getAttribute($name, $default = null)
    {

        $attributes = array_merge($this->_attributes, $this->_updatedAttributes);

        return isset($attributes[$name]) ? $attributes[$name] : $default;
    }

    /**
     *
     * Get bulk of field values
     *
     * @param array $attributes
     * @return array
     */
    public function getAttributes(array $attributes)
    {

        return array_intersect_key(array_flip($attributes), array_merge($this->_attributes, $this->_updatedAttributes));
    }

    /**
     * Reset all changes to original values
     */
    public function reset()
    {

        $this->_updatedAttributes = [];

        $this->_isDirty = false;
    }

    /**
     * @return bool Is model dirty, should it be updated (saved)
     */
    public function dirty()
    {

        return $this->_isDirty;
    }

    /**
     *
     * Validates does model (entity) exists in storage
     *
     * @return bool
     */
    public function exists()
    {

        return null !== $this->primaryValue();
    }

    /**
     * @param string $decoratorClassName Class name of wanted decorator
     * @param array $decoratorParameters Parameters that will be passed to decorator constructor
     * @return IDecorator
     */
    public function decorate($decoratorClassName, array $decoratorParameters = [])
    {

        if (empty($this->_instantiatedDecorators[$decoratorClassName])) {

            $this->_instantiatedDecorators[$decoratorClassName] = Decorator::decorate($decoratorClassName, $this, $decoratorParameters);
        }

        return $this->_instantiatedDecorators[$decoratorClassName];
    }

    /**
     *
     * Retrieve all parameters with its associated values, parameters stored in "_hidden" property won't be retrieved in array
     *
     * @return array
     */
    public function toArray()
    {

        return array_diff_key(array_merge($this->_attributes, $this->_updatedAttributes), array_flip($this->_hidden));
    }

    /**
     *
     * Generate INSERT query used for saving
     *
     * @param string $table Table name
     * @param string $primary Name of primary key
     * @param array $attributes Field values
     * @return string
     */
    private function _buildSaveQuery($table, $primary, array $attributes)
    {

        $fields = array_keys($attributes);

        $updateFields = $fields;

        $primaryIdx = array_search($primary, $updateFields);

        if (false !== $primaryIdx) {

            unset($updateFields[$primaryIdx]);
        }

        $fieldMapper = function ($key) {

            return "`{$key}`=?";
        };

        $fieldsString = implode(',', array_map($fieldMapper, $fields));

        $updateFieldsString = implode(',', array_map($fieldMapper, $updateFields));

        $query = "INSERT INTO `{$table}` SET {$fieldsString} ON DUPLICATE KEY UPDATE {$updateFieldsString};";

        return $query;
    }

    /**
     *
     * Build select query with "WHERE" criteria
     *
     * @param string $table
     * @param array $criteria
     * @return string
     */
    private function _buildCriteriaSelectQuery($table, array $criteria)
    {

        $mapFunction = function ($field) {
            return "`{$field}` = ?";
        };

        $fields = implode(' AND ', array_map($mapFunction, array_keys($criteria)));

        return "SELECT * FROM `{$table}` WHERE {$fields};";
    }
}