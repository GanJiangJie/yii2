<?php

namespace app\common\service;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class BaseService
{
    /**
     * @var integer $page
     */
    public $page;
    /**
     * @var integer $limit
     */
    public $limit;
    /**
     * @var integer $total
     */
    protected $total;
    /**
     * @var integer $last_page
     */
    protected $last_page;

    /**
     * 参数挂载
     * BaseService constructor.
     * @param array $params
     * @param array $keys
     */
    public function __construct($params = [], $keys = [])
    {
        foreach ($keys as $key) {
            $this->$key = $params[$key] ?? null;
        }
    }

    /**
     * 挂载参数
     * @param array $params
     * @param array $keys
     */
    public function loadParams($params = [], $keys = [])
    {
        foreach ($keys as $key) {
            $this->$key = $params[$key] ?? null;
        }
    }

    /**
     * 生成随机且不重复12位编号
     * @param ActiveRecord $model
     * @param string $attribute
     * @return string
     */
    public static function createCode12(ActiveRecord $model, $attribute = '')
    {
        do {
            $code = '' . mt_rand(100000000000, 999999999999);
            $exists = $model::find()
                ->where($attribute . ' = :' . $attribute, [
                    ':' . $attribute => $code
                ])
                ->exists();
        } while ($exists);
        return $code;
    }

    /**
     * 查询分页
     * @param ActiveQuery $model
     */
    protected function queryPage(ActiveQuery &$model)
    {
        $this->total = $model->count();
        if ($this->total > 0 && $this->limit > 0) {
            $this->last_page = (int)(ceil($this->total / $this->limit));
            if ($this->page > $this->last_page) {
                $this->page = $this->last_page;
            }
            $model->offset(($this->page - 1) * $this->limit)->limit($this->limit);
        }
    }

    /**
     * 分页返回
     * @param array $list
     * @return array
     */
    protected function returnPage($list)
    {
        return [
            'list' => $list,
            'page' => (int)$this->page,
            'limit' => (int)$this->limit,
            'total' => (int)$this->total,
            'last_page' => $this->last_page
        ];
    }
}