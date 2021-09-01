<?php

namespace app\common\service;

use app\common\event\model\MemberRegisterEvent;
use app\common\constant\Constant as C;
use app\models\Member;
use yii\base\Exception;

class MemberService extends BaseService
{
    public $merchant_code;
    public $type;
    public $member_name;
    public $account;
    public $birthday;

    /**
     * @return array
     */
    public function getList(): array
    {
        $members = Member::find()
            ->select([
                'member_code',
                'member_name',
                'nick_name',
                'account',
                'birthday',
                'create_time',
            ])
            ->where('merchant_code = :merchant_code and type = :type', [
                ':merchant_code' => $this->merchant_code,
                ':type' => $this->type
            ])
            ->orderBy('id desc');

        $this->account and $members->where('account = :account', [
            ':account' => $this->account
        ]);

        self::queryPage($members);

        foreach ($members->each() as $member) {
            $this->list[] = [
                'member_code' => $member['member_code'],
                'member_name' => $member['member_name'],
                'nick_name' => $member['nick_name'],
                'account' => $member['account'],
                'birthday' => $member['birthday'],
                'age' => $member['birthday'] ? birthdayToAge($member['birthday']) : null,
                'create_time' => $member['create_time']
            ];
        }

        return self::makePage();
    }

    /**
     * @throws Exception
     */
    public function register()
    {
        Member::find()
            ->where('merchant_code = :merchant_code and account = :account', [
                ':merchant_code' => $this->merchant_code,
                ':account' => $this->account
            ])
            ->exists() and throwE('会员已存在');

        $member = new Member();
        $member->member_code = createCode($member, 'member_code');
        $member->merchant_code = $this->merchant_code;
        $member->save() or throwE(json_encode($member->getErrors()), C::API_ERROR_CODE_SYSTEM_ERROR);

        //会员注册事件
        event(new MemberRegisterEvent($member->toArray()));
    }

    /**
     * @throws Exception
     */
    public function edit()
    {
        /**
         * @var Member $member
         */
        $member = Member::find()
            ->where('merchant_code = :merchant_code and account = :account', [
                ':merchant_code' => $this->merchant_code,
                ':account' => $this->account
            ])
            ->one() or throwE('会员不存在', C::API_ERROR_CODE_NO_DATA);

        $this->member_name and $member->member_name = $this->member_name;
        $this->birthday and $member->birthday = $this->birthday;

        $member->save() or throwE(json_encode($member->getErrors()), C::API_ERROR_CODE_SYSTEM_ERROR);
    }
}