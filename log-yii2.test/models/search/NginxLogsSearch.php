<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\NginxLogs;
use kartik\daterange\DateRangeBehavior;

/**
 * NginxLogsSearch represents the model behind the search form of `app\models\NginxLogs`.
 */
class NginxLogsSearch extends NginxLogs
{
    public $stime;
    public $etime;

    public function behaviors()
    {
        return [
            [
                'class' => DateRangeBehavior::className(),
                'attribute' => 'time',
                'dateStartAttribute' => 'stime',
                'dateEndAttribute' => 'etime',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'sentBytes', 'created_at', 'updated_at', 'stime', 'etime'], 'integer'],
            [['ip', 'file', 'request', 'referer', 'user_agent'], 'safe'],
            [['time'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = NginxLogs::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'time' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->orFilterWhere(['status' => $this->request])
            ->orFilterWhere(['sentBytes' => $this->request])
            ->orFilterWhere(['like', 'request', $this->request])
            ->orFilterWhere(['like', 'referer', $this->request])
            ->orFilterWhere(['like', 'user_agent', $this->request]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'file', $this->file])
            ->andFilterWhere(['>=', 'time', $this->stime])
            ->andFilterWhere(['<', 'time', $this->etime]);

        return $dataProvider;
    }
}
