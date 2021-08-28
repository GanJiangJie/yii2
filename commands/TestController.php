<?php

namespace app\commands;

use app\components\ConsoleController;
use app\models\Member;
use yii\base\Exception;

class TestController extends ConsoleController
{
    public function actionIndex()
    {
        dd(getDirFile(BASE_PATH.'/common'));

        1 > 2 and dd(123);
        try {
            Member::find()
                ->where('account = :account', [
                    ':account' => '13554157913'
                ])
                ->exists()
            and
            throwBaseException('会员已经存在');
        } catch (Exception $exception) {
            dd($exception->getMessage(), $exception->getCode());
        }

        //$a = '';
        //dd(Validator::isJson($a));

        false or var_dump(123);
        dd(456);

        $t1 = true and false;
        $t2 = true && false;
        $t3 = false or true;
        $t4 = false || true;
        dd($t1, $t2, $t3, $t4);
    }
}