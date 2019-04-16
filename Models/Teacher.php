<?php

class Teacher{

	public $fullName; // полное имя преподавателя

	public function __construct($fullNameTeacher){//конструктор
        $this->fullName=$fullNameTeacher;
    }
}