<?php
namespace Application\Models;

class TodoList{
    private $list;
    private $id;
    private $name;
    
    public function __construct($id=null){
        $this->list = [];

        if(isset($id)){
            if(isset($_SESSION['todos'][$id])){
                $this->list = $_SESSION['todos'][$id]['list'];
                $this->name = $_SESSION['todos'][$id]['name'];
                $this->id = $id;
            }
        }
    }

    public function add(int $i,string $todo){
        if(isset($todo) && $i >= 0 && $i <= count($this->list)){
            array_splice($this->list,$i,0,$todo);
        }
        return $this;
    }

    public function remove(int $i){
        if($i >= 0 && $i < count($this->list)){
            array_splice($this->list,$i,1);
        }
        return $this;
    }

    public function size(){
        return count($this->list);
    }
    
    public function save(){
        if(!isset($this->id)){
            $this->id = count($_SESSION['todos']);
        }

        $_SESSION['todos'][$this->id] = ['list'=>$this->list,'name'=>$this->name,'id'=>$this->id];

        return $this;
    }

    public function id(){
        return $this->id;
    }
    
    public function getName(){
        return $this->name;
    }
    
    public function setName($name=''){
        $this->name = $name;
        return $this;
    }

    public function asArray(){
        return $this->list;
    }

}