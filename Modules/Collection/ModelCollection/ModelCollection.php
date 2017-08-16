<?php

namespace Modules\Collection\ModelCollection;

use Modules\Collection\TypedCollection\TypedCollection;
use Modules\Database\Database;
use Modules\Exception\Exceptions\DatabaseException;
use Modules\Exception\Exceptions\ModelCollectionException;
use Modules\Model\IModel;

abstract class ModelCollection extends TypedCollection implements IModelCollection
{

    /** @var Database */
    private $_db;

    /** @var IModel */
    private $_model;

    protected function __construct(array $models = [])
    {

        parent::__construct($models);

        $this->_db = Database::getSharedInstance();

        $this->_model = forward_static_call([$this->getObjectType(), 'getSharedInstance']);
    }

    /**
     *
     * Fetch from database and fill current instance of collection with hydrated models of given type
     *
     * @param array $criteria
     * @return $this|IModelCollection
     * @throws ModelCollectionException
     */
    public function fetch(array $criteria)
    {

        $whereClauses = [];
        $bindings = [];

        foreach ($criteria as $field => $criterion) {

            // If field (key) is number and its value is array, threat it as sub condition that will be separated with OR
            if (is_numeric($field) && is_array($criterion)) {

                $whereClauses[] = $this->_buildWhereClause($criterion);
                $bindings = array_merge($bindings, array_values($criterion));
            } else {

                $whereClauses[] = $this->_buildWhereClause($criteria);
                $bindings = array_merge($bindings, array_values($criteria));

                break;
            }
        }

        $query = "SELECT * FROM `{$this->_model->table()}` WHERE (" . implode(') OR (', $whereClauses) . ');';

        $results = $this->_fetchModels($query, $bindings);

        if (is_array($results)) {

            foreach ($results as $modelData) {

                $this[] = forward_static_call_array([$this->getObjectType(), 'getNewInstance'], [$modelData, false]);
            }
        }

        return $this;
    }

    /**
     *
     * Returns collection in type of array
     *
     * @return array
     */
    public function toArray()
    {

        $array = [];

        /** @var IModel $model */
        foreach ($this->_items as $model) {

            $array[] = $model->toArray();
        }

        return $array;
    }

    /**
     *
     * Fetches and returns all entities that matches given query criteria
     *
     * @param string $query
     * @param array $bindings
     * @return array|null
     * @throws ModelCollectionException
     */
    private function _fetchModels($query, array $bindings = [])
    {
        try {

            return $this->_db->fetchAll($query, $bindings);
        } catch (DatabaseException $exception) {

            $callee = static::class;

            if ($exception->getCode() === 20002) {

                $class = get_class($this->_model);

                throw new ModelCollectionException(ModelCollectionException::ERROR_INVALID_MODEL_TYPE, "Got {$class} in {$callee}");
            }

            throw new ModelCollectionException(ModelCollectionException::ERROR_UNKNOWN, $callee);
        }
    }

    /**
     *
     * Generate WHERE Clause string used for fetching models from storage
     *
     * @param array $criteria
     * @return string
     */
    private function _buildWhereClause(array $criteria)
    {

        $mapFunction = function ($field) {
            return "`{$field}` = ?";
        };

        $fields = implode(' AND ', array_map($mapFunction, array_keys($criteria)));

        return $fields;
    }
}