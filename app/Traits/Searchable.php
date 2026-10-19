<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\{Log, Schema};
use Exception;

trait Searchable
{
    protected $query;

    /**
     * Searches model by either columns or attributes
     * 
     * @param string|array|object $value
     * @throws Exception
     * @return Builder
     */
    public function search($value)
    {
        $attributes = $this->getFillable();
        // TODO get unguarded
        // if (!count($attributes)) {
            
        // }

        $searchableRelations = $this->searchableRelations;

        $this->query = $this->newQuery();
        if ($value) {
            if (gettype($value) == "string") {
                $value = json_decode($value);
            }
            if (gettype($value) !== "integer" && gettype($value) !== "string") { // searching by array (model & its relations search specific columns)
                foreach ($value as $columnName => $columnValue) { // searching each requested relation
                    $this->searchModelColumns([$columnName => $columnValue], $attributes);
                    $this->searchModelRelationalColumns([$columnName => $columnValue], $searchableRelations);
                }
            } else { // searching by string (model & its relations search all fillables)
                throw new Exception('Invalid filter array format. Visit # for more details.');
                // $this->searchModelAttributes($value, $attributes);
                // $this->searchModelRelationalAttributes($value, $searchableRelations);
            }
        }

        return $this->query;
    }

    /**
     * TODO: remove this logic as it causes performance issue
     * Searches model fillable attributes
     * 
     * @param string|array|object $value
     * @param array $attributes
     * @throws Exception
     * @return Builder
     */
    protected function searchModelAttributes($value, $attributes)
    {
        if ($attributes) {
            array_map(function ($attribute) use ($value) {

                if (gettype($value) === 'array' || gettype($value) === 'object') {

                    // if column is json search for multiple values in the json content
                    if (Schema::getColumnType($this->getTable(), $attribute) == 'json') {
                        $this->query->where(function($q) use ($value, $attribute) {
                            foreach ($value as $v) {
                                $q->orWhere($attribute, 'LIKE', '%'.$v.'%');
                            }
                        });
                    } else {
                        $this->query->whereIn($attribute, $value);
                    }

                    // if (count($value) > 1) {
                    //     $this->query->orWhereBetween($attribute, $value);
                    // }
                } else {
                    if ($value === "null") 
                        $this->query->whereNull($attribute);
                    else
                        $this->query->where($attribute, 'LIKE', '%' . $value . '%');
                }
            }, $attributes);
        }
        return $this->query;
    }

    /**
     * TODO: remove this logic as it causes performance issue
     * Searches model's related models fillable attributes
     * 
     * @param string|array|object $value
     * @param array $searchableRelations
     * @return Builder
     */
    protected function searchModelRelationalAttributes($value, $searchableRelations)
    {
        if ($searchableRelations) {

            array_map(function ($relation) use ($value) {

                $relatedClass = get_class($this->$relation()->getRelated());
                $relatedClass = new $relatedClass;
                $table = $relatedClass->getTable();
                $attributes = $relatedClass->getFillable();
                // TODO get unguarded
                // if (!count($attributes)) {
                    
                // }

                array_map(function ($attribute) use ($value, $relation, $table) {

                    $this->query->whereHas($relation, function ($q) use ($table, $value, $attribute) {

                        if (gettype($value) === 'array' || gettype($value) === 'object') {

                            // if column is json search for multiple values in the json content
                            if (Schema::getColumnType($table, $attribute) == 'json') {
                                $q->where(function($q) use ($value, $attribute) {
                                    foreach ($value as $v) {
                                        $q->orWhere($attribute, 'LIKE', '%'.$v.'%');
                                    }
                                });
                            } else {
                                $q->whereIn($table . '.' . $attribute, $value);
                            }

                            // if (count($value) > 1) {
                            //     $q->orWhereBetween($table . '.' . $attribute, $value);
                            // }
                        } else {
                            if ($value === "null") 
                                $this->query->whereNull($attribute);
                            else
                                $q->where($table . '.' . $attribute, 'LIKE', '%' . $value . '%');
                        }
                    });
                }, $attributes);
            }, $searchableRelations);
        }

        return $this->query;
    }

    /**
     * Searches model specific fillable attributes
     * 
     * @param array $data
     * @param array $attributes
     * @throws Exception
     * @return Builder
     */
    protected function searchModelColumns($data, $attributes)
    {
        if ($data) {
            array_walk($data, function ($value, $column) use ($attributes) {
                if (strpos($column, '.')) {
                    return;
                };

                // TODO: change static string id to primary key of table
                if (!in_array($column, $attributes) && $column != 'id') {
                    return;
                    // stopping the search with exception caused the relational search to break
                    // TODO: add failed searches to response in array for user to see which properties faile
                    // throw new \Exception("Invalid search property ($column).");
                }

                if (gettype($value) === 'array' || gettype($value) === 'object') {

                    // if column is json search for multiple values in the json content
                    if (Schema::getColumnType($this->getTable(), $column) == 'json') {
                        $this->query->where(function($q) use ($value, $column) {
                            foreach ($value as $v) {
                                $q->orWhere($column, 'LIKE', '%'.$v.'%');
                            }
                        });
                    } else if (
                        Schema::getColumnType($this->getTable(), $column) == 'timestamp' || 
                        Schema::getColumnType($this->getTable(), $column) == 'date' ||
                        Schema::getColumnType($this->getTable(), $column) == 'datetime'
                    ) {
                        $this->query->whereBetween($column, $value);
                    } else {
                        $this->query->whereIn($column, $value);
                    }

                    // if (count($value) > 1) {
                    //     $this->query->orWhereBetween($column, $value);
                    // }

                } else {
                    if ($value === "null")  {
                        $this->query->whereNull($column);
                    } else {
                        // TODO: change static string id to check if column is foreign key of table
                        if (strpos($column, 'id') !== false) {
                            $this->query->where($column, "=", (int)$value);
                        } else {
                            $this->query->where($column, 'LIKE', '%' . $value . '%');
                        }
                    }
                }
            });
        }
        return $this->query;
    }

    /**
     * Searches model's related models specific fillable attributes
     * 
     * @param array $data
     * @param array $attributes
     * @throws Exception
     * @return Builder
     */
    protected function searchModelRelationalColumns($data, $searchableRelations)
    {
        // TODO must check if relation exists in the searchableRelations to search it or not
        // if ($searchableRelations) {

            array_walk($data, function ($value, $column) {

                $lookup = explode('.', $column);

                if (count($lookup) < 2) {
                    return;
                }

                $column = array_pop($lookup);                
                $relations = array_diff($lookup, [$column]);

                $relation = implode('.', $relations);

                $relatedClass = $this;
                foreach (explode('.', $relation) as $relatedRelation) {
                    $relatedClass = get_class($relatedClass->$relatedRelation()->getRelated());
                    $relatedClass = new $relatedClass;
                }

                $table = $relatedClass->getTable();
                $attributes = $relatedClass->getFillable();
                
                // TODO get unguarded
                // if (!count($attributes)) {
                    
                // }

                // TODO: change static string id to primary key of table
                if (!in_array($column, $attributes) && $column != 'id') {
                    return;
                    // stopping the search with exception caused the relational search to break
                    // throw new \Exception("Invalid search property ($column).");
                }

                $this->query->whereHas($relation, function ($q) use ($table, $column, $value) {

                    if (gettype($value) === 'array' || gettype($value) === 'object') {

                        // if column is json search for multiple values in the json content
                        if (Schema::getColumnType($table, $column) == 'json') {
                            $q->where(function($q) use ($value, $column) {
                                foreach ($value as $v) {
                                    $q->orWhere($column, 'LIKE', '%'.$v.'%');
                                }
                            });
                        } else if (
                            Schema::getColumnType($table, $column) == 'timestamp' || 
                            Schema::getColumnType($table, $column) == 'date' ||
                            Schema::getColumnType($table, $column) == 'datetime'
                        ) {
                            $q->whereBetween($column, $value);
                        } else {
                            $q->whereIn($table . '.' . $column, $value);
                        }

                        // if (count($value) > 1) {
                        //     $q->orWhereBetween($table . '.' . $column, $value);
                        // }
                    } else {
                        if ($value === "null") {
                            $this->query->whereNull($column);
                        } else {
                            // TODO: change static string id to check if column is foreign key of table
                            if (strpos($column, 'id') !== false) {
                                $q->where($table . '.' . $column, $value);
                            } else {
                                $q->where($table . '.' . $column, 'LIKE', '%' . $value . '%');
                            }
                        }
                    }
                });
            });
        // }

        return $this->query;
    }
}
