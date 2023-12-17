<?php

namespace app\models\form;

use app\models\Statistics;
use Faker\Core\Number;
use yii\base\Model;

class StatisticsForm extends Model
{
    public $curs;
    public $date;
    public $id;

    public function rules() {
        return [
            [['curs', 'date'], 'string'],
            [['id'], 'integer']
        ];
    }
    public function send($curs) {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Statistics();

        $goods->curs = $curs;
        $goods->date = date("Y-m-d");

        $status = $goods->save();

        return $status;
    }
}
