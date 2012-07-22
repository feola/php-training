<?php

require_once 'PHPUnit/Autoload.php';
include_once 'app.php';

class ModelTest extends PHPUnit_Framework_TestCase {
  function testModelFile() {
    $this->assertFileExists('pic.lst');
    $oModel = new ModelFile();
    $this->assertTrue($oModel->issetId(0));
    $this->assertEquals('04.jpg', $oModel->getName(0));
  }

  function testView() {
    $oView = new View(new ModelFile());
    $this->assertEquals('exist=0', $oView->chg(0, "exist=%d", "not-exist=%d"));
    $this->assertEquals('not-exist=999', $oView->chg(999, "exist=%d", "not-exist=%d"));
    $this->assertEquals('not-exist=-1', $oView->chg(-1, "exist=%d", "not-exist=%d"));
  }

  function testController() {
    $oController = new Controller(new View(new ModelFile()));
    $this->assertEquals(0, $oController->getCurrId());
    $_GET['id'] = "7";
    $this->assertEquals(7, $oController->getCurrId());
    $this->assertEquals('exist=6', $oController->prv("exist=%d", "not-exist=%d"));
    $this->assertEquals('exist=8', $oController->nxt("exist=%d", "not-exist=%d"));
    $_GET['id'] = "0";
    $this->assertEquals(0, $oController->getCurrId());
    $this->assertEquals('not-exist=-1', $oController->prv("exist=%d", "not-exist=%d"));
    $this->assertEquals('exist=1', $oController->nxt("exist=%d", "not-exist=%d"));
    $_GET['id'] = "17";
    $this->assertEquals(17, $oController->getCurrId());
    $this->assertEquals('exist=16', $oController->prv("exist=%d", "not-exist=%d"));
    $this->assertEquals('not-exist=18', $oController->nxt("exist=%d", "not-exist=%d"));
  }

}

/* function init_db() */
/* { */
/*   $aPics = explode("\n", trim(file_get_contents('pic.lst'))); */
/*   mysql_connect('localhost', 'root', 'root'); */
/*   mysql_select_db('pic'); */
/*   mysql_query('DROP TABLE IF EXISTS `pics`'); */
/*   mysql_query('CREATE TABLE `pics` (NAME VARCHAR(255))'); */
/*   foreach($aPics as $v) { */
/*     mysql_query("INSERT INTO `pics` VALUES ('$v')"); */
/*   } */
/* } */

/* init_db(); */

/* select * from pics; */
/* +------------------+ */
/* | NAME             | */
/* +------------------+ */
/* | 04.jpg           | */
/* | 118-200.jpg      | */
/* | 11.jpg           | */
/* | 165-200.jpg      | */
/* | 196-200.jpg      | */
/* | 209-200.jpg      | */
/* | 211-200.jpg      | */
/* | 217-200.jpg      | */
/* | 232-200.jpg      | */
/* | 238-200.jpg      | */
/* | 243-200.jpg      | */
/* | 254-200.jpg      | */
/* | 257-200.jpg      | */
/* | 260-200.jpg      | */
/* | 2d9c79cd2470.jpg | */
/* | 2.jpeg           | */
/* | 7b96cf9fce38.gif | */
/* | 93-200.jpg       | */
/* +------------------+ */
/* 18 rows in set (0.00 sec) */
