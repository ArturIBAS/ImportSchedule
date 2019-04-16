<?php

class Schedule{

	public $group; //номер группы
	public $teacher1; //ФИО преподавателя
	public $teacher2; //ФИО второго преподавателя (если есть)
	public $class; //название занятия
	public $parityWeek; //чётность недели
	public $dayWeek; //день недели
	public $timeClass; //время занятия

	public function __construct($group,$teacher1,$teacher2,$class,$parityWeek,$dayWeek,$timeClass){//конструктор
        $this->group=$group;
        $this->teacher1=$teacher1;
        $this->teacher2=$teacher2;
        $this->class=$class;
        $this->parityWeek=$parityWeek;
        $this->dayWeek=$dayWeek;
        $this->timeClass=$timeClass;
    }
}