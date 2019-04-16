<?php

require_once "DB.php";
require_once "Models/Group.php";
require_once "Models/Class.php";
require_once "Models/Teacher.php";
require_once "Models/Schedule.php";
require_once 'Classes/PHPExcel.php';

class ExcelController{

public function getObjPHPExcel($filename){ //возвращает объект PHPExcel для работы с файлом
	$file_type = PHPExcel_IOFactory::identify( $filename );
	$objReader = PHPExcel_IOFactory::createReader( $file_type );
	$objPHPExcel = $objReader->load( $filename );
	return $objPHPExcel;
}

public function getSheetCount($filename){ //возвращает количество листов в файле
	
	$objPHPExcel = $this->getObjPHPExcel($filename);

	return $objPHPExcel->getSheetCount();
}


public function getSpecialty($filename){ //Возвращает название специальности, для которой составлено расписание

	$objPHPExcel = $this->getObjPHPExcel($filename);
	$headStr = $objPHPExcel->getActiveSheet()->toArray()[0];
	$specialty='';

	foreach ($headStr as $cell) {
		if (isset($cell)) $specialty=$cell;
	}

	return $specialty;

}

public function getListGroups($filename){ //возвращает массив списка групп
	
	$objPHPExcel = $this->getObjPHPExcel($filename);

	$listGroups=[];

	$aGroups = $objPHPExcel->getActiveSheet()->toArray()[1];
	
	$i=1;

	$c='A';

	foreach ($aGroups as $group) {
		if (isset($group)) $listGroups[$c.'2']=$group;
		$c=++$c;
	}

	return $listGroups;
}

public function getListTeachersAndClasses($filename){

	$schedules=$this->getScheduleFromTable($filename);
	$teachers=[];
	$classes=[];

	foreach ($schedules as $key => $schedule) {
		if($schedule->teacher1!=='-') $teachers[]=$schedule->teacher1;
		if($schedule->teacher2!=='-') $teachers[]=$schedule->teacher2;
		if($schedule->class!=='' || $schedule->class!=='-') $classes[]=$schedule->class;
	}

	$teachers=array_unique($teachers);
	$classes=array_unique($classes);

	$teachersAndClasses=[];

	$teachersAndClasses['teachers']=$teachers;
	$teachersAndClasses['classes']=$classes;

	return $teachersAndClasses;

}

public function isInMergeRange($filename,$adressCell){ //возвращает true, если ячейка имеет слияние с другой, иначе - false

	$objPHPExcel = $this->getObjPHPExcel($filename);

	$cell=$objPHPExcel->getActiveSheet()->getCell($adressCell);
	
	return $cell->isInMergeRange();

}

public function getDiapason($filename,$adressCell){ //возвращает строку вида: "A3:A7", определяющую диапазон слитых  клеток

	$objPHPExcel = $this->getObjPHPExcel($filename);

	$cell=$objPHPExcel->getActiveSheet()->getCell($adressCell);

	return $cell->getMergeRange();

}

public function getDiapasonMergeDay($filename){ //возвращает массив слитых клеток дней недели в расписании

	$diapasonDays=[];

	$adressCell='A3';


	for ($i=1; $i<=6 ; $i++) { 
		$diapasonDays[$i]=$this->getDiapason($filename,$adressCell);
		$adressCell=++explode(":", $diapasonDays[$i])[1];
	}

	return $diapasonDays;
	
}



public function getLengthDiapason($diapasonCell){

	$arr=explode(":", $diapasonCell);

	$arr1=str_split($arr[0]);

	$char1=array_shift($arr1);

	$num1=implode($arr1);

	$arr2=str_split($arr[1]);

	$char2=array_shift($arr2);

	$num2=implode($arr2);

	return $num2-$num1;

}



public function getStartAndEndRow($str){ //возвращает номер начальной строки дня недели и номер конечной

	$arr=explode(":", $str);

	$arrNumb=[];


	foreach ($arr as $cell) {

		$arrRes[]=str_replace("A", "", $cell);

	}

	return $arrRes;

}

public function getArrLengthDiapason($diapasonDays){ //возвращает массив длин слитых клеток дней недели

	$arrLengthDiapason=[];

	for ($i=1; $i<=6 ; $i++) { 
		$arrLengthDiapason[$i]=$this->getLengthDiapason($diapasonDays[$i]);
	}

	return $arrLengthDiapason;
}


public function getCellValue($filename,$adressCell){ //возвращает строку вида: "A3:A7", определяющую диапазон слитых  клеток

	$objPHPExcel = $this->getObjPHPExcel($filename);

	return (string)$objPHPExcel->getActiveSheet()->getCell($adressCell);

}

public function getDayWeek($i){
	switch ($i) {

		case 1:
			return 'Понедельник';
			break;

		case 2:
			return 'Вторник';
			break;

		case 3:
			return 'Среда';
			break;

		case 4:
			return 'Четверг';
			break;

		case 5:
			return 'Пятница';
			break;

		case 6:
			return 'Суббота';
			break;
		
		
	}
}

	// public $group; //номер группы----------------------------------------------
	// public $teacher; //ФИО преподавателя---------------------------------------
	// public $class; //название занятия-----------------------------------------
	// public $parityWeek; //чётность недели-----------------------------------
	// public $dayWeek; //день недели------------------------------------------
	// public $timeClass; //время занятия

public function determineDayWeek($filename, $numRow){

	$diapasonDays=$this->getDiapasonMergeDay($filename);

	$intervalRows=[];

	$day='';

	$i=1;

	foreach ($diapasonDays as $diapasonDay) {
		$intervalRows[]=$this->getStartAndEndRow($diapasonDay);
	}

	foreach ($intervalRows as $intervalRow) {
		
		if($numRow>=$intervalRow[0] && $numRow<=$intervalRow[1]){
			switch ($i) {

				case 1:
					$day='Понедельник';
					break;
				
				case 2:
					$day='Вторник';
					break;

				case 3:
					$day='Среда';
					break;

				case 4:
					$day='Четверг';
					break;

				case 5:
					$day='Пятница';
					break;

				case 6:
					$day='Суббота';
					break;
			
			}
		}
		$i++;
	}

	return $day;

}

public function getClassForOneTeacher($str){
 
 $arr=explode(' ', $str);
 $teacher='';
 $result=[];
 $result['teacher1']='';
 $result['teacher2']='';
 $result['class']='';

 foreach ($arr as $key => $value) {
 	
 	if(mb_strlen($value)==4){

 		$aValue = preg_split('//u', $value, NULL, PREG_SPLIT_NO_EMPTY);

 		if($aValue[1]==='.' && $aValue[3]==='.'){

 		$teacher=$arr[$key-1].' '.$value;
 		
 		unset($arr[$key-1]);

 		unset($arr[$key]);

 	}

 	}

 }

 $class=implode(" ", $arr);

 if($teacher==='') $result['teacher1']='-';
 else $result['teacher1']=trim($teacher);
 $result['class']=trim($class);
 $result['teacher2']='-';

return $result;
}

public function getClassForTwoTeacher($str,$del){
	
	$arr=explode($del, $str);
	$arr1=$this->getClassForOneTeacher($arr[0]);
	$result=[];
	$result['teacher1']=trim($arr1['teacher1']);
 	$result['class']=trim($arr1['class']);
 	$result['teacher2']=trim($arr[1]);
 	return $result;

}

public function splitClassAndTeachers($str){

	$arr = preg_split('//u', $str, NULL, PREG_SPLIT_NO_EMPTY);
	$flag=false;
	$result=[];
	$del='/';

	foreach ($arr as $key => $value) {
		
		if($value=='/'){

			$flag=true;
			if($arr[$key+1]==' ') $del='/ ';
			

		};

		if($value==="\n") $str=$str=str_replace("\n", ' ', $str);

	}

	switch ($flag) {
		
		case true:
			
			$result=$this->getClassForTwoTeacher($str,$del);

			break;
		
		case false:
			
			$result=$this->getClassForOneTeacher($str);

			break;
	}

	return $result;

}

public function makeSchedule($filename, $cellAdress, $cellWithTime){ //Возвращает одну клетку таблицы
	
	/****/
	$group='';
	$teacher1='';
	$teacher2='';
	$class='';
	$dayWeek='';
	$timeClass='';
	/****/

	$group=$this->getCellValue($filename,$cellAdress);


	$arr1=str_split($cellAdress);

	$charGroup=array_shift($arr1);

	$numGroup=implode($arr1);

	$arr2=str_split($cellWithTime);

	$charTime=array_shift($arr2);

	$numTime=implode($arr2);

	$dayWeek=$this->determineDayWeek($filename,$numTime);

	$cellAdress=$charGroup.$numTime;

	$timeClass=$this->getCellValue($filename,$cellWithTime);

	$schedulesPar=[];


	if($this->isInMergeRange($filename, $cellWithTime)){

		if($this->isInMergeRange($filename, $cellAdress)){

			$cellValue=$this->splitClassAndTeachers($this->getCellValue($filename,$cellAdress));

			$class=$cellValue['class'];

			$teacher1=$cellValue['teacher1'];

			$teacher2=$cellValue['teacher2'];

			if($class!=='') $schedulesPar[]=new Schedule($group, $teacher1, $teacher2 ,$class, 0, $dayWeek, $timeClass);

		}else{

			$cellValue=$this->splitClassAndTeachers($this->getCellValue($filename,$cellAdress));

			$class=$cellValue['class'];

			$teacher1=$cellValue['teacher1'];

			$teacher2=$cellValue['teacher2'];

			$schedulesPar[]=new Schedule($group, $teacher1,$teacher2, $class, 1, $dayWeek, $timeClass);
		
			$cellAdress=++$cellAdress;

			$cellValue=$this->splitClassAndTeachers($this->getCellValue($filename,$cellAdress));

			$class=$cellValue['class'];

			$teacher1=$cellValue['teacher1'];

			$teacher2=$cellValue['teacher2'];

			if($class!=='') $schedulesPar[]=new Schedule($group, $teacher1,$teacher2, $class, 2, $dayWeek, $timeClass);
	}

	}else{

			$cellValue=$this->splitClassAndTeachers($this->getCellValue($filename,$cellAdress));

			$class=$cellValue['class'];

			$teacher1=$cellValue['teacher1'];

			$teacher2=$cellValue['teacher2'];

		if($class!=='') $schedulesPar[]=new Schedule($group, $teacher1, $teacher2 ,$class, 0, $dayWeek, $timeClass);
	}


	// $schedule=new Schedule($group, $teacher, $class, $parityOfWeek, $dayWeek, $timeClass);

	return $schedulesPar;
}

public function getStartAndEndOfFile($filename){

	$interval=[];

	$diapasonDaysWeek=$this->getDiapasonMergeDay($filename);

	$monday=explode(':',$diapasonDaysWeek[1])[0];

	$saturday=explode(':',$diapasonDaysWeek[6])[1];

	$arr1=str_split($monday);

	unset($arr1[0]);

	$start=implode($arr1);

	$arr2=str_split($saturday);

	unset($arr2[0]);

	$end=implode($arr2);

	if($this->isInMergeRange($filename,'B'.$end)) $end--;

	$result=[];
	$result['start']=$start;
	$result['end']=$end;

	return $result;

}

public function getScheduleFromTable($filename){

$schedules=[];

$interval=$this->getStartAndEndOfFile($filename);

$aListGroups=$this->getListGroups($filename);

 foreach ($aListGroups as $cellAdress => $nameGroup) {

	for ($bInd=$interval['start']; $bInd <=$interval['end']; $bInd++) { 
			
		 foreach ($this->makeSchedule($filename,$cellAdress,'B'.(string)$bInd) as $schedule) {
		 	$schedules[]=$schedule;

		 }

		 if($this->getObjPHPExcel($filename)->getActiveSheet()->getCell('B'.(string)$bInd)->isInMergeRange()) $bInd++;
	}

 }

return $schedules;

}



}