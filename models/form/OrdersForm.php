<?php

namespace app\models\form;

use app\models\Orders;
use Faker\Core\Number;
use yii\base\Model;

class OrdersForm extends Model
{
    public $number;
    public $id_goods;
    public $phone;
    public $name;
    public $price;
    public $active;
    public $info;
    public $geo;
    public $id;
    public $address;
    public $bonus;
    public $status;
    public $user_id;
    public $count;

    public function rules() {
        return [
            [['number', 'id_goods', 'phone', 'name', 'info', 'geo', 'address'], 'string'],
            [[ 'id', 'price', 'active', 'bonus', 'status', 'user_id', 'count'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Orders();
        $count = $goods::find()->orderBy('id DESC')->one();

        if($count) {
            $number = 'z' . $count->id + 1;
        } else {
            $number = 'z1';
        }

        $goods->number = $number;
        $goods->id_goods = $this->id_goods;
        $goods->phone = $this->phone;
        $goods->name = $this->name;
        $goods->info = $this->info;
        $goods->geo = $this->geo;
        $goods->address = $this->address;
        $goods->update = date("Y-m-d H:i:s");
        $goods->create = date("Y-m-d H:i:s");
        $goods->price = $this->price;
        $goods->active = 1;
        $goods->bonus = 0;
        $goods->count = 0;
        $goods->user_id = 1;
        $goods->status = 1;

        $status = $goods->save();

        return $status;
    }
    public function update() {
        if(!$this->validate()) {
            return null;
        }
        $goods = new Orders();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->id_goods = $this->id_goods;
        $goods->phone = $this->phone;
        $goods->name = $this->name;
        $goods->info = $this->info;
        $goods->geo = '['.trim(trim($this->geo, "["), "]").']';
        $goods->address = $this->address;
        $goods->update = date("Y-m-d H:i:s");
        $goods->price = $this->price;

        $status = $goods->save();

        return $status;
    }
    public function activate() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Orders();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->status = $this->status;
        $goods->update = date("Y-m-d H:i:s");

        $status = $goods->save();

        return $status;
    }
    public function delete() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Orders();
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
