<?php

class Group{

	public $number; //номер группы

	public function __construct($numGroup){//конструктор
        $this->number=$numGroup;
    }

    public function getId(){
    	$pdo=DB::getInstance();
    	return $pdo->query("SELECT `id` FROM `groups` WHERE `number`= '$this->number'")->fetch()['id'];
    }
}