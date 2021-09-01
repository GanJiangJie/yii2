<?php

namespace app\common\service;

use yii\db\ActiveQuery;

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
    public function assignAttrs(array $params = [], array $keys = [])
    {
        foreach ($keys as $key) {
            $this->$key = $params[$key] ?? null;
        }
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
    protected function makePage(): array
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