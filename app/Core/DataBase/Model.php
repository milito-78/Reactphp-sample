<?php
namespace App\Core\DataBase;

use App\Core\Container\NotFoundException;
use NilPortugues\Sql\QueryBuilder\Builder\GenericBuilder;
use NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder;
use NilPortugues\Sql\QueryBuilder\Manipulation\QueryInterface;
use React\MySQL\QueryResult;
use React\Promise\PromiseInterface;
use React\Promise\RejectedPromise;
use ReflectionClass;
use Exception;


class Model extends DB
{

    protected string $table = "table";
    private $props = [];
    /**
     * @var GenericBuilder|MySqlBuilder
     */
    protected $builder = null;

    public function __construct()
    {
        parent::__construct();
        $this->builder = new GenericBuilder();
        $this->props = func_get_args();
    }

    public function __get($name)
    {
        if (property_exists($this , $name)){
            return $this->{$name};
        }

        $parsed_body = $this->props[0];
        if (isset($parsed_body[$name]))
        {
            return $parsed_body[$name];
        }

        return null;
    }


    /**
     * @param $query
     * @param array $bind
     * @return PromiseInterface|RejectedPromise
     */
    public function query($query , $bind = [])
    {
        if (!count($bind))
        {
            return $this->db->query($query);
        }

        $query = preg_replace("/:\w+\d+/" ,"?", $query);
        return  $this->db->query($query , array_values($bind) );
    }

    public function all()
    {
        $build = $this->builder
            ->select()
            ->setTable($this->table);

        $query = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                            ->then(function (QueryResult $result){
                                                return $this->makeMap($result->resultRows);
                                            });
    }

    public function find($id)
    {

        $build = $this->builder
            ->select()
            ->setTable($this->table)
            ->where()
            ->equals("id" , $id)
            ->end()
            ->limit(0,1);

        $query = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                                ->then(function (QueryResult $result)
                                                {
                                                    if (!count($result->resultRows))
                                                        throw new NotFoundException("not found");

                                                    return $this->makeOneMap($result->resultRows[0]);
                                                });
    }

    public function findByColumn($column ,$id)
    {
        $build = $this->builder
            ->select()
            ->setTable($this->table)
            ->where()
            ->equals($column , $id)
            ->end()
            ->limit(0,1);

        $query = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                                ->then(function (QueryResult $result){
                                                    if (!count($result->resultRows))
                                                        throw new NotFoundException("not found");

                                                    return $this->makeOneMap($result->resultRows[0]);
                                                });
    }

    public function create($data)
    {
        $build = $this->builder
            ->insert()
            ->setTable($this->table)
            ->setValues($data);

        $query = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                                ->then(function (QueryResult $result) use ($data){
                                                    return $this->find($result->insertId);
                                                });
    }

    public function update($id,$data)
    {
        $build = $this->builder
            ->update()
            ->setTable($this->table)
            ->setValues($data)
            ->where()
            ->equals('id', $id)
            ->end();

        $query = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                                ->then(function (QueryResult $result) use ($id){
                                                    return $this->find($id);
                                                });
    }

    public function delete($id)
    {
        $build = $this->builder
            ->delete()
            ->setTable($this->table)
            ->where()
            ->equals("id" , $id)
            ->end()
            ->limit(0,1);

        $query  = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                                ->then(function (QueryResult $result){
                                                    return true;
                                                });

    }

    public function softDelete($id)
    {
        $build = $this->builder
            ->update()
            ->setTable($this->table)
            ->setValues(["deleted_at" => 'now()'])
            ->where()
            ->equals("id" , $id)
            ->end()
            ->limit(0,1);

        $query  = $this->builder->write($build);
        $values = $this->builder->getValues();

        return $this->query($query , $values)
                                                ->then(function (QueryResult $result){
                                                    return true;
                                                });
    }

    public static function build(GenericBuilder $builder ,QueryInterface $query)
    {
        $query  = $builder->write($query);
        $values = $builder->getValues();

        return (new self())->query($query , $values);
    }


    protected function makeMap($data) : array
    {
        $class = $this->getMapClassName();

        return $this->map($data ,$class);
    }

    protected function makeOneMap($data) : object
    {
        $class = $this->getMapClassName();

        return new $class($data);
    }

    protected function map($data ,$class)
    {
        return array_map(function ($row) use ($class){
            return new $class($row);
        },$data);
    }


    private function getMapClassName(){
        $ref = new ReflectionClass($this);
        $class = $ref->getName();

        if (!is_subclass_of($this,self::class) && is_a($this , self::class))
        {
            $class = Model::class;
        }
        elseif(!is_subclass_of($this,self::class))
        {
            throw new Exception("$class is not instance or subclass of Model class",500);
        }

        return $class;
    }


    public function toArray(): array
    {
        $array = [];
        foreach ($this->props[0] as $key => &$item)
            $array[$key] = $item;
        return $array;
    }
}