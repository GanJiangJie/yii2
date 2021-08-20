<?php

namespace app\commands;

use app\common\event\model\DefaultEvent;
use app\components\ConsoleController;
use app\models\Member;

class TestController extends ConsoleController
{
    public function actionIndex()
    {
        dd(params('mns.queue.listen'),BASE_PATH);
        $str = '吼吼吼aaa哈哈哈bbb嘿嘿嘿O(∩_∩)O哈哈~';
        $del = mb_strlen($str, 'utf-8') > 18 ? mb_substr($str, 0, 18, 'utf-8') : $str;
        dd($del);
        /**
         * @var Member $member
         */
        $member = Member::find()->where('account = :account', [':account' => '13554157913'])->one();
        $member->member_name = 'JieG';
        $member->birthday = '2000-01-01';
        $res = $member->save();
        dd($res, $member->toArray());
        $res = event(new DefaultEvent(['merchant_code' => '123', 'store_code' => '456']));
        var_dump($res);
    }
}