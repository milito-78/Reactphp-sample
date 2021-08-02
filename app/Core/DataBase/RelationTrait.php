<?php
namespace App\Core\DataBase;

use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;

trait RelationTrait
{
    /**
     * @var GenericBuilder
     */
    protected $builder;
    private function many_to_many($relation)
    {
        $build =  $this->builder
                                ->select($relation["select"])
                                ->setTable($relation["table"])
                                ->where()
                                ->in($relation["relation_id"] , ['*'])
                                ->end()
                                ->limit(0,1);
    }

    public function one_to_many($relation)
    {

    }

    public function many_to_one($relation)
    {

    }
}