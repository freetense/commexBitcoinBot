<?php

namespace app\models\form;

use app\models\Settings;
use yii\base\Model;

class SettingsForm extends Model
{
    public $id;
    public $maxs;
    public $plecho;
    public $intervals;
    public $news;
    public $api;
    public $secret;
    public $dis;

    public function rules() {
        return [
            [['maxs','plecho','intervals','news','api','secret'], 'string'],
            [['id', 'dis'], 'integer']
        ];
    }
    public function update() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Settings();
        $goods = $goods::find()->one();

        $goods->max = $this->maxs;
        $goods->plecho = (string) $this->plecho;
        $goods->intervals = (string) $this->intervals;
        $goods->new = (string) $this->news;
        $goods->api = (string) $this->api;
        $goods->secret = (string) $this->secret;
        $goods->dis = $goods->dis;

        $status = $goods->save();

        return $status;
    }
    public function stop() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new Settings();
        $goods = $goods::find()->one();

        $goods->max = $goods->max;
        $goods->plecho = $goods->plecho;
        $goods->intervals = $goods->intervals;
        $goods->new = $goods->new;
        $goods->api = $goods->api;
        $goods->secret = $goods->secret;
        $goods->dis = $this->dis;

        $status = $goods->save();

        return $status;
    }
}
