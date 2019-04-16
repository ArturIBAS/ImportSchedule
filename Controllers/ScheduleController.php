<?php

require "DB.php";
require "Models/Group.php";
require "Models/Class.php";
require "Models/Teacher.php";
require "Models/Schedule.php";

class ScheduleController{

public function getAllGroups(){ //возвращает массив массивов с данными о группах
    
    $pdo=DB::getInstance();
    $listGroups=[];

    $aGroups=$pdo->query("SELECT * FROM `groups`")->fetchAll();

    foreach ($aGroups as $group) {
    	$listGroups[]=new Group($group['number']);
    }

    return $listGroups;
}

public function getSchedule($groupNum,$day,$parityWeek){

	$pdo=DB::getInstance();
	$group=new Group($groupNum);
	$groupId=$group->getId();

	switch ($parityWeek) {
		case 'Чётная':
			$parityWeek=(int)$parityWeek;
			$parityWeek=2;
			break;

		case 'Нечётная':
			$parityWeek=(int)$parityWeek;
			$parityWeek=1;
			break;	
		
		default:
		    $parityWeek=(int)$parityWeek;
			$parityWeek=0;
			break;
	}

	$aSchedule=$pdo->query("SELECT * FROM `schedules` WHERE `group_id`= $groupId AND `day_of_the_week`= '$day' AND (`parity_week`= $parityWeek OR `parity_week`=0)")->fetchAll();

	if($aSchedule==null){
		return false;
	}else{

		$schedules=[];

		foreach ($aSchedule as $schedule) {
			
			$teacher1Id=$schedule['teacher1_id'];

			$teacher2Id=$schedule['teacher2_id'];

			$classId=$schedule['сlass_id'];

			if($teacher1Id===NULL && $teacher2Id===NULL){

			if($classId===NULL){

				$schedules[]=new Schedule($groupNum, '-', '-', 'Окно', $parityWeek, $day, $schedule['time_of_class']);

			}

			
			else{ 
			$class=new studyClass($pdo->query("SELECT `name` FROM `classes` WHERE `id`= $classId")->fetch()['name']);
			$schedules[]=new Schedule($groupNum, '-', '-',$class->name, $parityWeek, $day, ' ');
			}


			}else{



			$teacher1=new Teacher($pdo->query("SELECT `full_name` FROM `teachers` WHERE `id`= $teacher1Id")->fetch()['full_name']);

			if($teacher2Id===NULL) $teacher2=new Teacher('-');
			else $teacher2=new Teacher($pdo->query("SELECT `full_name` FROM `teachers` WHERE `id`= $teacher2Id")->fetch()['full_name']);

			$class=new studyClass($pdo->query("SELECT `name` FROM `classes` WHERE `id`= $classId")->fetch()['name']);

			$schedules[]=new Schedule($groupNum, $teacher1->fullName, $teacher2->fullName, $class->name, $parityWeek, $day, $schedule['time_of_class']);
		}

		}


		// var_dump($schedules);
	

	return $schedules;
}

}


}