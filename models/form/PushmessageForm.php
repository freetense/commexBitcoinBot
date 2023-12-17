<?php

namespace app\models\form;

use app\models\Push;
use app\models\Pushmessage;
use yii\base\Model;

class PushmessageForm extends Model
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
            [['link', 'description', 'icon', 'title', 'create', 'update'], 'string'],
            [['id'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Pushmessage();

        $goods->icon = $this->icon;
        $goods->description = $this->description;
        $goods->title = $this->title;
        $goods->link = '';
        $goods->create = date("Y-m-d H:i:s");
        $goods->update = date("Y-m-d H:i:s");

        $status = $goods->save();

        return $status;
    }
}
