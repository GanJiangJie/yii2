<?php

namespace app\commands;

use app\components\ConsoleController;

class TestController extends ConsoleController
{
    public function actionIndex()
    {
        dd(app()->params['mns']['queue']['listen']);
        dd(loopFolderGetFiles(BASE_PATH . '/common'));
    }
}