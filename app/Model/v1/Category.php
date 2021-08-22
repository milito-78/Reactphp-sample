<?php
namespace App\Model\v1;

use App\Core\DataBase\Model;
use App\Core\Request\Request;

class Category extends Model
{
    protected string $table = "categories";

    public static function index(?Request $request = null)
    {
        $self = new self();
        $page = 1;
        $per_page = per_page();

        $build = $self->builder
            ->select()
            ->setTable($self->table);

        $build_count = $self->builder
            ->select()
            ->setTable($self->table);


        if ($request->has("parent") && $request->input("parent")){
            $build->where()->equals("parent_id" , $request->input("parent"))->end();
            $build_count->where()->equals("parent_id" , $request->input("parent"))->end();
        }
        else
        {
            $build->where()->isNull("parent_id")->end();
            $build_count->where()->isNull("parent_id")->end();
        }

        if ($request->has("page")){
            $page = $request->input("page");
        }
        if ($request->has("per_page")){
            $per_page = $request->per_page;
        }

        $build->where()->equals("status" , "active")->isNull("deleted_at")->end();
        $build_count->where()->equals("status" , "active")->isNull("deleted_at")->end();


        $build_count->count("*" , "categories");



        $count_query = $self->builder->write($build_count);
        $count_values = $self->builder->getValues();


        return $self->query($count_query,$count_values)->then(function ($res) use ($build,$per_page , $page , $request , $self){

            $count = $res->resultRows[0]["categories"];
            $page_count =  ceil($count/$per_page);

            $offset = ($page - 1) * $per_page;

            $build->limit($offset,$per_page);

            $query = $self->builder->write($build);
            $values = $self->builder->getValues();

           return $self->query($query , $values)->then(function ($result)use($self,$count , $page , $per_page , $page_count){
               return [
                   "data" => $self->makeMap($result->resultRows),
                   "meta" => [
                       "current"    => (int)$page,
                       "per_page"   => (int)$per_page,
                       "total"      => (int)$count,
                       "pages"      => (int) $page_count
                   ]
               ];
           });
        });
    }


    public function JsonResource(): array
    {
        if ($this->parent_id){
            return [
                "id"            => (int)$this->id,
                "title"         => $this->title,
                "parent_id"     => (int)$this->parent_id,
            ];
        }else{
            return [
                "id"    => (int)$this->id,
                "title" => $this->title,
                "logo"  => $this->logo,
            ];
        }
    }

}