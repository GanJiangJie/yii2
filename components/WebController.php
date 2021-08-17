<?php

namespace app\components;

use yii\web\Controller;

class WebController extends Controller
{
    /**
     * @param $action
     * @return bool
     */
    public function beforeAction($action)
    {
        return true;
    }
}