<?php

namespace app\common\service;

use app\common\constant\Constant as C;
use app\common\event\model\MemberRegisterEvent;
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
     * @var Member $member
     */
    private $member;

    /**
     * MemberService constructor.
     */
    public function __construct()
    {
        $this->member = new Member();
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        $members = $this->member::find()
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

        $this->account and $members->where('account = :account', [
            ':account' => $this->account
        ]);

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

    /**
     * @throws Exception
     */
    public function register()
    {
        $this->member::find()
            ->where('merchant_code = :merchant_code and account = :account', [
                ':merchant_code' => $this->merchant_code,
                ':account' => $this->account
            ])
            ->exists() and throwBaseException('会员已存在');

        $this->member->member_code = self::createCode($this->member, 'member_code');
        $this->member->merchant_code = $this->merchant_code;
        $this->member->save() or throwBaseException(json_encode($this->member->getErrors()), C::API_ERROR_CODE_SYSTEM_ERROR);

        //会员注册事件
        event(new MemberRegisterEvent($this->member->toArray()));
    }

    /**
     * @throws Exception
     */
    public function edit()
    {
        /**
         * @var Member $member
         */
        $member = $this->member::find()
            ->where('merchant_code = :merchant_code and account = :account', [
                ':merchant_code' => $this->merchant_code,
                ':account' => $this->account
            ])
            ->one() or throwBaseException('会员不存在', C::API_ERROR_CODE_NO_DATA);

        $this->member_name and $member->member_name = $this->member_name;
        $this->birthday and $member->birthday = $this->birthday;

        $member->save() or throwBaseException(json_encode($member->getErrors()), C::API_ERROR_CODE_SYSTEM_ERROR);
    }
}