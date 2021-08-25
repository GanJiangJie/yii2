<?php

namespace app\commands;

use app\components\ConsoleController;

class TestController extends ConsoleController
{
    public function actionIndex()
    {
        dd(loopFolderGetFiles(BASE_PATH . '/common'));
    }
}