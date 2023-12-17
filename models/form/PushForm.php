<?php

namespace app\models\form;

use app\models\Push;
use yii\base\Model;

class PushForm extends Model
{
    public $link;
    public $description;
    public $title;
    public $icon;
    public $create;
    public $update;
    public $id;

    public function rules() {
        return [
            [['description', 'icon', 'title', 'create', 'update'], 'string'],
            [['id'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Push();

        $goods->icon = $this->icon;
        $goods->description = $this->description;
        $goods->title = $this->title;
        $goods->link = "";
        $goods->create = date("Y-m-d H:i:s");
        $goods->update = date("Y-m-d H:i:s");

        $status = $goods->save();

        return $status;
    }
    public function update() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Push();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->icon = $this->icon;
        $goods->description = $this->description;
        $goods->title = $this->title;
        $goods->update = date("Y-m-d H:i:s");


        $status = $goods->save();

        return $status;
    }
    public function delete() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Push();
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
