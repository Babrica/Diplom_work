<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

class UserSearch extends User
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['full_name', 'username', 'email', 'number', 'role'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // Если данные валидации не прошли, просто вернем провайдер данных без фильтрации
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'role', $this->role]);

        return $dataProvider;
    }
}