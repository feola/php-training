<?php

class BaseModel {
  protected $aPics;

  public function issetId($id)
  {
    return isset($this->aPics[$id]);
  }

  public function getName ($id)
  {
    if ($this->issetId($id)) {
      return $this->aPics[$id];
    } else {
      return false;
    }
  }
}


class ModelFile extends BaseModel {
  function __construct()
  {
    $this->aPics = explode("\n", trim(file_get_contents('pic.lst')));
  }
}


class ModelDb extends BaseModel {
  function __construct()
  {
    mysql_connect('localhost', 'root', 'root');
    mysql_select_db('pic');
    $result = mysql_query('SELECT * FROM `pics`');
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
      $this->aPics[] = $row[0];
    }
  }
}



class View {
  protected $oModel;

  function __construct($oModel)
  {
    $this->oModel = $oModel;
  }

  public function chg ($newId, $frmt_ok, $frmt_none)
  {
    if ($this->oModel->issetId($newId)) {
      return sprintf($frmt_ok, $newId);
    } else {
      return sprintf($frmt_none, $newId);
    }
  }

}


class Controller {
  protected $oView;

  function __construct($oView)
  {
    $this->oView = $oView;
  }

  function getCurrId() {
    if (isset($_GET['id'])) {
      return intval($_GET['id']);
    }
    return 0;
  }

  public function prv ($frmt_ok, $frmt_none)
  {
    return $this->oView->chg($this->getCurrId()-1, $frmt_ok, $frmt_none);
  }

  public function nxt ($frmt_ok, $frmt_none)
  {
    return $this->oView->chg($this->getCurrId()+1, $frmt_ok, $frmt_none);
  }

}



function main($model_spec = 'file')
{
  switch ($model_spec) {
  case 'file' : $myModel = new ModelFile(); break;
  case 'db'   : $myModel = new ModelDb(); break;
  default     : throw new Exception('Unknown model spec');
  }
  $myView       = new View($myModel);
  $myController = new Controller($myView);

  echo($myController->prv('<a href="?id=%d"><-</a>', ""));

  if (false === $myModel->getName($myController->getCurrId())) {
    echo('NoPic'.$myModel->getName($myController->getCurrId()));
  } else {
    echo('<img src="/pic/'.$myModel->getName($myController->getCurrId()).'" />');
  }

  echo($myController->nxt('<a href="?id=%d">-></a>', ""));

}




