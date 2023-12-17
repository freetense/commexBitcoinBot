<?php

namespace app\models\form;

use app\models\Stock;
use yii\base\Model;

class StockForm extends Model
{
    public $photo;
    public $description;
    public $title;
    public $date_to;
    public $date_from;
    public $active;
    public $init_date;
    public $id;

    public function rules() {
        return [
            [['photo', 'description', 'active', 'title', 'date_to', 'date_from', 'init_date'], 'string'],
            [['id'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Stock();

        $goods->photo = $this->photo;
        $goods->description = $this->description;
        $goods->active = $this->active;
        $goods->title = $this->title;
        $goods->date_to = $this->date_to;
        $goods->date_from = $this->date_from;

        $goods->init_date = date("Y-m-d H:i:s");

        $status = $goods->save();

        return $status;
    }
    public function update() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Stock();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->photo = $this->photo;
        $goods->description = $this->description;
        $goods->active = $this->active;
        $goods->title = $this->title;
        $goods->date_to = $this->date_to;
        $goods->date_from = $this->date_from;

        $status = $goods->save();

        return $status;
    }
    public function activate() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Stock();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->active = $this->active;

        $status = $goods->save();

        return $status;
    }
    public function delete() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Stock();
        $status = $goods::find()
            ->where(['id' => $this->id])
            ->one();
        if($status) {
            $status = $status->delete();
        } else {
            return null;
        }
        return $status;
    }
}
