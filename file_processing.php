<!DOCTYPE html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap material admin template">
    <meta name="author" content="">
    
    <title>Расписание занятий</title>
    
    <link rel="apple-touch-icon" href="../../assets/images/apple-touch-icon.png">
    <link rel="shortcut icon" href="../../assets/images/favicon.ico">
    <link rel="stylesheet" href="/css/style.css">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="../../../global/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="../../assets/css/site.min.css">
    
    <!-- Plugins -->
    <link rel="stylesheet" href="../../../global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="../../../global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="../../../global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="../../../global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="../../../global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="../../../global/vendor/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="../../../global/vendor/waves/waves.css">
      <link rel="stylesheet" href="../../../global/vendor/bootstrap-table/bootstrap-table.css">
      <link rel="stylesheet" href="../../../../global/fonts/web-icons/web-icons.css">
      <link rel="stylesheet" href="../../../global/vendor/nprogress/nprogress.css">
      <link rel="stylesheet" href="../../assets/examples/css/advanced/animation.css">
    
    
    <!-- Fonts -->
    <link rel="stylesheet" href="../../../global/fonts/material-design/material-design.min.css">
    <link rel="stylesheet" href="../../../global/fonts/brand-icons/brand-icons.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    
    <!--[if lt IE 9]>
    <script src="../../../global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
    
    <!--[if lt IE 10]>
    <script src="../../../global/vendor/media-match/media.match.min.js"></script>
    <script src="../../../global/vendor/respond/respond.min.js"></script>
    <![endif]-->
    
    <!-- Scripts -->
    <script src="../../../global/vendor/breakpoints/breakpoints.js"></script>
    <script>
      Breakpoints();
    </script>
  </head>
  <body class="animsition site-menubar-unfold site-menubar-keep">

    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
    
      <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
          data-toggle="menubar">
          <span class="sr-only">Toggle navigation</span>
          <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
          data-toggle="collapse">
          <i class="icon md-more" aria-hidden="true"></i>
        </button>

      </div>
    
      <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
          <!-- Navbar Toolbar -->
          <ul class="nav navbar-toolbar">
            <li class="nav-item hidden-float" id="toggleMenubar">
              <a class="nav-link" data-toggle="menubar" href="#" role="button">
                <i class="icon hamburger hamburger-arrow-left">
                  <span class="sr-only">Toggle menubar</span>
                  <span class="hamburger-bar"></span>
                </i>
              </a>
            </li>
            <li class="nav-item hidden-sm-down" id="toggleFullscreen">
              <a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                <span class="sr-only">Toggle fullscreen</span>
              </a>
            </li>
            <span class="align-header">Расписание</span>
          </ul>
          <!-- End Navbar Toolbar -->
        </div>
        <!-- End Navbar Collapse -->
   
      </div>
    </nav>    

    <div class="site-menubar">
      <div class="site-menubar-body">
        <div>
          <div>

            <ul class="site-menu" data-plugin="menu">

              <li class="site-menu-item">
                <a class="animsition-link" href="../index.php">
                        <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                        <span class="site-menu-title">Расписание занятий</span>
                    </a>
              </li>
      
              
              <li class="site-menu-item">
                <a class="animsition-link" href="../upload.php">
                   <i class="site-menu-icon md-upload" aria-hidden="true" style="font-size: 20px;"></i>
                  <span class="site-menu-title">Добавить расписание</span>
                </a>
              </li>

            </ul>

          </div>
        </div>
      </div>
  </div>   


    <!-- Page -->
    <div class="page">

      <div class="page-content">
        <div class="panel">

          <div class="panel-body">
            
<!--             <div class="example-wrap">
              <h4 class="example-title">Round Circle</h4>
              <div class="example-loading example-well h-150 vertical-align text-center loaderArea">
                <div class="loader vertical-align-middle loader-round-circle loader"></div>
              </div>
            </div> -->

              <?php

              require "Controllers/ExcelController.php";
              require "Controllers/XMLController.php";
              ini_set('max_execution_time', 1800);

              $typeFile='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

        
              if(isset($_FILES) && $_FILES['inputfile']['error'] == 0){ // Проверяем, загрузил ли пользователь файл

              if($_FILES['inputfile']['type']===$typeFile){

               $dirGroups=dirname(__FILE__) . '/File/xml/groups/';
               $dirTeachers=dirname(__FILE__) . '/File/xml/teachers/';
               $dirClasses=dirname(__FILE__) . '/File/xml/classes/';
               $dirSchedules=dirname(__FILE__) . '/File/xml/schedules/';

              $nameId= uniqid(date('d.m.y').'_', true);

              $destiation_dir = dirname(__FILE__) .'/File/excel/'.$nameId.'.xlsx'; // Директория для размещения файла .xlsx

              $resultUpload=move_uploaded_file($_FILES['inputfile']['tmp_name'], $destiation_dir);// true или false

              if($resultUpload==true){

                $timeStart = strtotime(date("H:i:s"));

                $excel=new ExcelController();
                $xml=new XMLController();

                $nameFileGroups=uniqid(date('d.m.y').'_').'.xml';
                $nameFileClasses=uniqid(date('d.m.y').'_').'.xml';
                $nameFileTeachers=uniqid(date('d.m.y').'_').'.xml';
                $nameFileSchedules=uniqid(date('d.m.y').'_').'.xml';

                $filenameGroups=$dirGroups.$nameFileGroups;
                $filenameClasses=$dirClasses.$nameFileClasses;
                $filenameTeachers=$dirTeachers.$nameFileTeachers;
                $filenameSchedules=$dirSchedules.$nameFileSchedules;


                $groups=$excel->getListGroups($destiation_dir); //записываем группы в xml и заносим в базу
                $xml->writeXML($filenameGroups, $xml->translateGroupsInXML($xml->checkTableGroups($groups)));
                $xml->inputGroupsInDB($filenameGroups);

                $classesAndTeachers=$excel->getListTeachersAndClasses($destiation_dir);
                $classes=$classesAndTeachers['classes'];
                $teachers=$classesAndTeachers['teachers'];

                $xml->writeXML($filenameTeachers, $xml->translateTeachersInXML($xml->checkTableTeachers($teachers)));//записываем учителей в xml и заносим в базу
                $xml->inputTeachersInDB($filenameTeachers);

                $xml->writeXML($filenameClasses, $xml->translateClassesInXML($xml->checkTableClasses($classes)));//записываем предметы в xml и заносим в базу
                $xml->inputClassesInDB($filenameClasses);

                $schedules=$excel->getScheduleFromTable($destiation_dir);

                $xml->writeXML($filenameSchedules, $xml->translateSchedulesInXML($schedules));//записываем расписание в xml и вносим в базу
                $xml->inputSchedulesInDB($filenameSchedules);

                $timeEnd = strtotime(date("H:i:s"));
                $loginTime=$timeEnd-$timeStart;

                echo "<h3 class='panel-title'> Загрузка окончена. Время загрузки: </h3>".date('H:i:s', $loginTime);
              }

              }else{

                echo "<h3 class='panel-title'>Извините, формат файла не подходит. Требуемый формат файла: xlsx. </h3>";

              }

              }else{
                 echo "<h3 class='panel-title'>Файл не был добавлен.</h3>";
              }

              ?>
              
        </div>
      </div>
    </div>
  </div>
    <!-- End Page -->


    <!-- Footer -->
    <footer class="site-footer">
      <div class="site-footer-legal">© 2019 <a href="http://themeforest.net/item/remark-responsive-bootstrap-admin-template/11989202">Buriev Artur</a></div>
    </footer>

    <!-- Core  -->
    <script src="../../../global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
    <script src="../../../global/vendor/jquery/jquery.js"></script>
    <script src="../../../global/vendor/popper-js/umd/popper.min.js"></script>
    <script src="../../../global/vendor/bootstrap/bootstrap.js"></script>
    <script src="../../../global/vendor/animsition/animsition.js"></script>
    <script src="../../../global/vendor/mousewheel/jquery.mousewheel.js"></script>
    <script src="../../../global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
    <script src="../../../global/vendor/asscrollable/jquery-asScrollable.js"></script>
    <script src="../../../global/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>
    <script src="../../../global/vendor/waves/waves.js"></script>
    
    <!-- Plugins -->
    <script src="../../../global/vendor/switchery/switchery.js"></script>
    <script src="../../../global/vendor/intro-js/intro.js"></script>
    <script src="../../../global/vendor/screenfull/screenfull.js"></script>
    <script src="../../../global/vendor/slidepanel/jquery-slidePanel.js"></script>
        <script src="../../../global/vendor/bootstrap-table/bootstrap-table.min.js"></script>
        <script src="../../../global/vendor/bootstrap-table/extensions/mobile/bootstrap-table-mobile.js"></script>
        <script src="../../../global/vendor/jquery-appear/jquery.appear.js"></script>
        <script src="../../../global/vendor/nprogress/nprogress.js"></script>

    
    <!-- Scripts -->
    <script src="../../../global/js/Component.js"></script>
    <script src="../../../global/js/Plugin.js"></script>
    <script src="../../../global/js/Base.js"></script>
    <script src="../../../global/js/Config.js"></script>
    
    <script src="../../assets/js/Section/Menubar.js"></script>
    <script src="../../assets/js/Section/GridMenu.js"></script>
    <script src="../../assets/js/Section/Sidebar.js"></script>
    <script src="../../assets/js/Section/PageAside.js"></script>
    <script src="../../assets/js/Plugin/menu.js"></script>
    
    <script src="../../../global/js/config/colors.js"></script>
    <script src="../../assets/js/config/tour.js"></script>
    <script>Config.set('assets', '../../assets');</script>
    
    <!-- Page -->
    <script src="../../assets/js/Site.js"></script>
    <script src="../../../global/js/Plugin/asscrollable.js"></script>
    <script src="../../../global/js/Plugin/slidepanel.js"></script>
    <script src="../../../global/js/Plugin/switchery.js"></script>

        <script src="../../assets/examples/js/tables/bootstrap.js"></script>

        <script src="../../../global/js/Plugin/jquery-appear.js"></script>
        <script src="../../../global/js/Plugin/nprogress.js"></script>
    
        <script src="../../assets/examples/js/advanced/animation.js"></script>

        <script>
          $(window).on('load', function () {
            $preloader = $('.loaderArea'),
            $loader = $preloader.find('.loader');
            $loader.fadeOut();
            $preloader.delay(350).fadeOut('slow');
          });
        </script>

    <script>
      (function(document, window, $){
        'use strict';
    
        var Site = window.Site;
        $(document).ready(function(){
          Site.run();
        });
      })(document, window, jQuery);

    </script>
    
  </body>
</html>
