<?php

namespace app\commands;

use app\components\ConsoleController;
use app\common\constant\{
    Constant as C,
    MemberC as MC
};

class TestController extends ConsoleController
{
    public function actionIndex()
    {
        printf("%b\n",10);dd();
        dd(C::API_ERROR_CODE_SUCCESS, MC::TYPE_MEMBER);
    }
}