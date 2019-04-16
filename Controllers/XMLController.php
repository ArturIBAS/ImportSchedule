<?php

require_once "ExcelController.php";


class XMLController{


 public function checkTableGroups($groups){

 		$pdo=DB::getInstance();
 		$excel=new ExcelController();
 		$filterListGroups=[];

 	foreach ($groups as $key => $group) {

		$aGroup=$pdo->query("SELECT * FROM `groups` WHERE `number`='$group'")->fetchAll();
		if(empty($aGroup)) $filterListGroups["$key"]=$group;
 	}



 	return $filterListGroups;
 }

 public function checkTableTeachers($teachers){

 	$pdo=DB::getInstance();
 	$excel=new ExcelController();
 	$filterListTeachers=[];

 	foreach ($teachers as $key => $teacher) {
 		$aTeacher=$pdo->query("SELECT * FROM `teachers` WHERE `full_name`='$teacher'")->fetchAll();
 		if(empty($aTeacher)) $filterListTeachers["$key"]=$teacher;
 	}

 	return $filterListTeachers;
 }

  public function checkTableClasses($classes){

 	$pdo=DB::getInstance();
 	$excel=new ExcelController();
 	$filterListClasses=[];

 	foreach ($classes as $key => $class) {
 		$aClass=$pdo->query("SELECT * FROM `classes` WHERE `name`='$class'")->fetchAll();
 		if(empty($aClass)) $filterListClasses["$key"]=$class;
 	}

 	return $filterListClasses;
 }

 public function translateGroupsInXML($groups){

 	$head="<?xml version='1.0' standalone='yes'?>
 	<groups>";

 	$content="";

 	foreach ($groups as $key => $group) {
 		
 		$content.="
 		<group>
 		<number>$group</number>
 		</group>
 		";
 	}

 	$footer="
 	</groups>";

 	return $head.$content.$footer;

 }


   public function translateSchedulesInXML($schedules){

 	$head="<?xml version='1.0' standalone='yes'?>
 	<schedules>";
 	$pdo=DB::getInstance();

 	$groupId='';
 	$teacher1Id='';
 	$teacher2Id='';
 	$classId='';

 	$content="";

 	foreach ($schedules as $key => $schedule) {
 		
 		$groupId=$pdo->query("SELECT id FROM `groups` WHERE `number`='$schedule->group'")->fetch()['id'];

 		$teacher1Id= ($schedule->teacher1==='-') ? 0 : $pdo->query("SELECT id FROM `teachers` WHERE `full_name`='$schedule->teacher1'")->fetch()['id'];

 		$teacher2Id= ($schedule->teacher2==='-') ? 0 : $pdo->query("SELECT id FROM `teachers` WHERE `full_name`='$schedule->teacher2'")->fetch()['id'];

 		$classId= ($schedule->class=='' || $schedule->class=='-') ? 0 : $pdo->query("SELECT id FROM `classes` WHERE `name`='$schedule->class'")->fetch()['id'];

 		$content.="
 		<schedule>
 		<group_id>$groupId</group_id>
 		<teacher1_id>$teacher1Id</teacher1_id>
 		<teacher2_id>$teacher2Id</teacher2_id>
 		<class_id>$classId</class_id>
 		<parity_week>$schedule->parityWeek</parity_week>
 		<day_of_the_week>$schedule->dayWeek</day_of_the_week>
 		<time_of_class>$schedule->timeClass</time_of_class>
 		</schedule>
 		";
 	}

 	$footer="
 	</schedules>";

 	return $head.$content.$footer;

 }


  public function translateTeachersInXML($teachers){

 	$head="<?xml version='1.0' standalone='yes'?>
 	<teachers>";

 	$content="";

 	foreach ($teachers as $key => $teacher) {
 		
 		$content.="
 		<teacher>
 		<full_name>$teacher</full_name>
 		</teacher>
 		";
 	}

 	$footer="
 	</teachers>";

 	return $head.$content.$footer;

 }

   public function translateClassesInXML($classes){

 	$head="<?xml version='1.0' standalone='yes'?>
 	<classes>";

 	$content="";

 	foreach ($classes as $key => $class) {
 		
 		$content.="
 		<class>
 		<name>$class</name>
 		</class>
 		";
 	}

 	$footer="
 	</classes>";

 	return $head.$content.$footer;

 }

 public function inputGroupsInDB($file){

 	$pdo=DB::getInstance();

 	$content = file_get_contents($file);

 	$groups=simplexml_load_string($content);

 	
 	foreach ($groups as $key => $group) {
 		$pdo->exec("INSERT INTO `groups` (`number`) VALUES('$group->number')");
 	}

 }

  public function inputTeachersInDB($file){

 	$pdo=DB::getInstance();

  	$content = file_get_contents($file);

 	$teachers=simplexml_load_string($content);

 	foreach ($teachers as $key => $teacher) {
 		$pdo->exec("INSERT INTO `teachers` (`full_name`) VALUES('$teacher->full_name')");
 	}

 }

  public function inputClassesInDB($file){

 	$pdo=DB::getInstance();

 	$content = file_get_contents($file);

 	$classes=simplexml_load_string($content);

 	foreach ($classes as $key => $class) {
 		$pdo->exec("INSERT INTO `classes` (`name`) VALUES('$class->name')");
 	}

 }

 public function inputSchedulesInDB($file){

 	$pdo=DB::getInstance();
 	$sql="";

 	$content = file_get_contents($file);

 	$schedules=simplexml_load_string($content);
 	
 	foreach ($schedules as $key => $schedule) {

 		$teacher1_id= ($schedule->teacher1_id==0) ? "NULL" : $schedule->teacher1_id;
 		$teacher2_id= ($schedule->teacher2_id==0) ? "NULL" : $schedule->teacher2_id;
 		$class_id= ($schedule->class_id==0) ? "NULL" : $schedule->class_id; 

 		$sql="INSERT INTO `schedules` (`group_id`,`teacher1_id`,`teacher2_id`,`сlass_id`,`parity_week`,`day_of_the_week`,`time_of_class`) VALUES ($schedule->group_id, $teacher1_id, $teacher2_id, $class_id, $schedule->parity_week, '$schedule->day_of_the_week', '$schedule->time_of_class')";

 			
 		$pdo->exec($sql);
 	}

 }

 public function writeXML($file, $content){

 	$my_file = fopen($file, "w") or die("Не удалось открыть файл!");
    fwrite($my_file,  $content);
    fclose($my_file);

 }



}

