<?php

namespace Modules\Database;


interface IDatabase
{

    /**
     *
     * Fetch single record from storage
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query
     * @return array|null
     */
    public function fetch($query, array $bindings = []);

    /**
     *
     * Fetch multiple record from storage
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query
     * @return array|null
     */
    public function fetchAll($query, array $bindings = []);

    /**
     *
     * Save record to storage and retrieve its id or number of affected rows if ID is not provided (e.g no primary key)
     * (INSERT, UPDATE queries)
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query execution
     * @return int
     */
    public function store($query, array $bindings);

    /**
     *
     * Execute query and return number of affected rows
     *
     * Meant to be used with delete queries
     *
     * @param string $query
     * @param array $bindings
     * @return int
     */
    public function delete($query, array $bindings);
}