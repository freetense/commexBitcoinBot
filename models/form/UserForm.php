<?php

namespace app\models\form;

use app\models\User;
use yii\base\Model;

class UserForm extends Model
{
    public $id;
    public $login;
    public $password;
    public $phone;
    public $name;
    public $fname;
    public $lname;
    public $key;
    public $bonus;
    public $day;
    public $pol;
    public $update;
    public $create;
    public $address;
    public $geo;
    public $photo;
    public $ban;

    public function rules() {
        return [
            [['login', 'password', 'phone', 'name', 'fname', 'lname', 'key', 'day', 'update', 'create', 'address', 'geo', 'photo', 'ban'], 'string'],
            [['id', 'bonus', 'pol'], 'integer']
        ];
    }
    public function send() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new User();
        $logins = $goods::find()->where(['login' => $this->login])->one();
        $pattern = '/[^0-9]/';
        $phones = $goods::find()->where(['like', 'phone', substr(preg_replace($pattern, "", $this->phone),-7)])->one();
        if(!$logins && !$phones) {
            $goods->fname = $this->fname;
            $goods->lname = $this->lname;
            $goods->name = $this->name;
            $goods->phone = $this->phone;
            $goods->login = $this->login;
            $goods->pol = $this->pol;
            $goods->day = $this->day;
            $goods->address = $this->address;
            $goods->password = md5($this->password);
            $goods->key = md5($this->password);
            $goods->ban = 'false';
            $goods->bonus = 0;
            $goods->geo = '[]';
            $goods->photo = '';

            $goods->update = date("Y-m-d H:i:s");
            $goods->create = date("Y-m-d H:i:s");

            $status = $goods->save();

            return $status;
        }else {
            return ['error' => [
                'login' => $logins ? true : false,
                'phone' => $phones ? true : false,
            ]];
        }
    }
    public function update() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new User();
        $goods = $goods::find()->where(['id' => $this->id])->one();
        $logins = $goods::find()
            ->where(['login' => $this->login])
            ->andWhere(['!=', 'id', $this->id])
            ->one();
        $pattern = '/[^0-9]/';
        $phones = $goods::find()
            ->where(['like', 'phone', substr(preg_replace($pattern, "", $this->phone),-7)])
            ->andWhere(['!=', 'id', $this->id])
            ->one();
        if(!$logins && !$phones) {
            $goods->fname = $this->fname;
            $goods->lname = $this->lname;
            $goods->name = $this->name;
            $goods->phone = $this->phone;
            $goods->login = $this->login;
            $goods->pol = $this->pol;
            $goods->day = $this->day;
            $goods->address = $this->address;
            $goods->password = $this->password;
            $goods->key = $this->password;
            $goods->ban = $this->ban;
            $goods->bonus = $this->bonus;
            $goods->geo = $this->geo;
            $goods->photo = $this->photo;

            $goods->update = date("Y-m-d H:i:s");
            $goods->create = $this->create;

            $status = $goods->save();

            return $status;
        }else {
            return ['error' => [
                'login' => $logins ? true : false,
                'phone' => $phones ? true : false,
            ]];
        }
    }
    public function activate() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new User();
        $goods = $goods::find()->where(['id' => $this->id])->one();

        $goods->ban = $this->ban;

        $status = $goods->save();

        return $status;
    }
    public function delete() {
        if(!$this->validate()) {
            return null;
        }

        $goods = new User();
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
