<?php

namespace app\common\service;

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
    public function getList()
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
    public function add()
    {
        $exists = $this->member::find()
            ->where('merchant_code = :merchant_code and account = :account', [
                ':merchant_code' => $this->merchant_code,
                ':account' => $this->account
            ])
            ->exists();
        if ($exists) {
            throw new Exception('会员已存在');
        }
        $this->member->member_code = self::createCode12($this->member, 'member_code');
        $this->member->merchant_code = $this->merchant_code;
        if (!$this->member->save()) {
            throw new Exception(json_encode($this->member->getErrors()), API_ERROR_CODE_SYSTEM_ERROR);
        }
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
            ->one();
        if (empty($member)) {
            throw new Exception('会员不存在', API_ERROR_CODE_NO_DATA);
        }
        if (!empty($this->member_name)) {
            $member->member_name = $this->member_name;
        }
        if (!empty($this->birthday)) {
            $member->birthday = $this->birthday;
        }
        if (!$member->save()) {
            throw new Exception(json_encode($member->getErrors()), API_ERROR_CODE_SYSTEM_ERROR);
        }
    }
}