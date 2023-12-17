<?php

namespace app\models\form;

use app\models\Products;
use yii\base\Model;
use Yii;

class ProductsScladForm extends Model
{
    public $id;
    public $title;
    public $id_name;
    public $price;
    public $status;
    public $weight;
    public $quantity;
    public $date_to;
    public $date_from;
    public $update;

    public function rules() {
        return [
            [['title', 'update', 'date_to', 'date_from', 'title', 'weight'], 'string'],
            [['id', 'status', 'id_name', 'price', 'quantity'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Products();

        $goods->title = $this->title;
        $goods->id_name = $this->id_name;
        $goods->price = $this->price;
        $goods->status = $this->status;
        $goods->weight = $this->weight;
        $goods->quantity = $this->quantity;

        $goods->date_to = date("Y-m-d H:i:s");
        $goods->date_from = date("Y-m-d H:i:s");
        $goods->update = date("Y-m-d H:i:s");
        $goods->create = date("Y-m-d H:i:s");

        $status = $goods->save();

        return $status;
    }
    public function update() {

        if(!$this->validate()) {
            return null;
        }

        $goods = new Products();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->update = date("Y-m-d H:i:s");

        $goods->title = $this->title;
        $goods->id_name = $this->id_name;
        $goods->price = $this->price;
        $goods->status = $this->status;
        $goods->weight = $this->weight;
        $goods->quantity = $this->quantity;

        $status = $goods->save();

        return $status;
    }

    public function delete() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Products();
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
