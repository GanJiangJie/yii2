<?php

namespace app\common\service;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class BaseService
{
    /**
     * @var int $page
     */
    public $page;
    /**
     * @var int $limit
     */
    public $limit;
    /**
     * @var int $total
     */
    protected $total;
    /**
     * @var int $last_page
     */
    protected $last_page;
    /**
     * @var array $list
     */
    protected $list = [];

    /**
     * 挂载参数
     * @param array $params
     * @param array $keys
     */
    public function assignAttributes(array $params = [], array $keys = [])
    {
        foreach ($keys as $key) {
            $this->$key = $params[$key] ?? null;
        }
    }

    /**
     * 生成随机且不重复12位编号
     * @param ActiveRecord $model
     * @param string $attribute
     * @param int $length
     * @return string
     */
    public static function createCode(ActiveRecord $model, string $attribute, int $length = 12): string
    {
        $start = (int)str_pad('1', $length, '0');
        $end = (int)str_pad('9', $length, '9');
        do {
            $code = '' . mt_rand($start, $end);
        } while (
            $model::find()
                ->where($attribute . ' = :' . $attribute, [
                    ':' . $attribute => $code
                ])
                ->exists()
        );
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
            $this->page > $this->last_page and $this->page = $this->last_page;
            $model->offset(($this->page - 1) * $this->limit)->limit($this->limit);
        }
    }

    /**
     * 分页返回
     * @return array
     */
    protected function returnPage(): array
    {
        return [
            'list' => $this->list,
            'page' => (int)$this->page,
            'limit' => (int)$this->limit,
            'total' => (int)$this->total,
            'last_page' => (int)$this->last_page
        ];
    }
}