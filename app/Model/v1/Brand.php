<?php
namespace App\Model\v1;

use App\Core\DataBase\Model;
use App\Core\Request\Request;

class Brand extends Model
{
    protected string $table = "brands";

    public static function index(?Request $request = null)
    {
        $self = new self();
        return $self->all();
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