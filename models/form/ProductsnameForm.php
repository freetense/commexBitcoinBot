<?php

namespace app\models\form;

use app\models\Productsname;
use yii\base\Model;
use Yii;

class ProductsnameForm extends Model
{

    public $title;
    public $id;
    public $update;

    public function rules() {
        return [
            [['title', 'update'], 'string'],
            [['id'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Productsname();
        $goods->update = date("Y-m-d H:i:s");
        $goods->create = date("Y-m-d H:i:s");
        $goods->title = $this->title;

        $status = $goods->save();

        return $status;
    }
    public function update() {

        if(!$this->validate()) {
            return null;
        }

        $goods = new Productsname();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->update = date("Y-m-d H:i:s");
        $goods->title = $this->title;

        $status = $goods->save();

        return $status;
    }

    public function delete() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Productsname();
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
