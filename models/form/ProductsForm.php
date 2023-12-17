<?php

namespace app\models\form;

use app\models\Statistics;
use Faker\Core\Number;
use yii\base\Model;

class ProductsForm extends Model
{
    public $avatar;
    public $description;
    public $bonus;
    public $photos;
    public $price;
    public $active;
    public $title;
    public $desabled;
    public $id;
    public $products;
    public $recept;

    public function rules() {
        return [
            [['products', 'recept', 'active', 'desabled', 'avatar', 'photos', 'title', 'description'], 'string'],
            [['bonus', 'price', 'id'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Statistics();

        $goods->avatar = $this->avatar;
        $goods->description = $this->description;
        $goods->bonus = $this->bonus;
        $goods->photos = $this->photos;
        $goods->price = $this->price;
        $goods->active = $this->active;
        $goods->update = date("Y-m-d H:i:s");
        $goods->create = date("Y-m-d H:i:s");
        $goods->title = $this->title;
        $goods->desabled = $this->desabled;
        $goods->products = $this->products;
        $goods->recept = $this->recept;

        $status = $goods->save();

        return $status;
    }
    public function update() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Statistics();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->avatar = $this->avatar;
        $goods->description = $this->description;
        $goods->bonus = $this->bonus;
        $goods->photos = $this->photos;
        $goods->price = $this->price;
        $goods->active = $this->active;
        $goods->update = date("Y-m-d H:i:s");
        $goods->title = $this->title;
        $goods->desabled = $this->desabled;
        $goods->products = $this->products;
        $goods->recept = $this->recept;

        $status = $goods->save();

        return $status;
    }
    public function activate() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Statistics();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->active = $this->active;
        $goods->update = date("Y-m-d H:i:s");

        $status = $goods->save();

        return $status;
    }
    public function deactivate() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Statistics();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->desabled = $this->desabled;
        $goods->update = date("Y-m-d H:i:s");

        $status = $goods->save();

        return $status;
    }
    public function delete() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Statistics();
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
