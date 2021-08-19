<?php

namespace app\common\service;

use app\models\Member;

class MemberService extends BaseService
{
    public $merchant_code;
    public $type;

    /**
     * @return array
     */
    public function getList()
    {
        $members = Member::find()
            ->select([
                'member_code',
                'member_name',
                'nick_name',
                'account'
            ])
            ->where('merchant_code = :merchant_code and type = :type', [
                ':merchant_code' => $this->merchant_code,
                ':type' => $this->type
            ])
            ->orderBy('id desc');
        self::queryPage($members);
        foreach ($members->each() as $member) {
            $this->list[] = [
                'member_code' => $member['member_code'],
                'member_name' => $member['member_name'],
                'nick_name' => $member['nick_name'],
                'account' => $member['account']
            ];
        }
        return self::returnPage();
    }
}