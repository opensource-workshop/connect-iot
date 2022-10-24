<?php

namespace App\PluginsOption\User\Receives;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\ModelsOption\User\Receives\Receive;
use App\ModelsOption\User\Receives\ReceiveData;
use App\ModelsOption\User\Receives\ReceiveRecord;
use App\ModelsOption\User\Receives\ReceiveView;
use App\ModelsOption\User\Receives\ReceiveCalc;
use App\ModelsOption\User\Receives\ReceiveAlert;
use App\User;

use Carbon\Carbon;

/**
 * データ受信のツール・クラス
 *
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category 課題管理プラグイン
 * @package Contoroller
 */
class ReceivesTool
{
    /**
     * ログインしているユーザ情報
     */
    private $user = null;

    /**
     * 表示設定
     */
    private $receive_views = null;

    /**
     * 集計設定
     */
    private $receive_calcs = null;

    /**
     * アラート設定
     */
    private $receive_alerts = null;

    /**
     * フレームid
     */
    private $frame_id = null;

    /**
     * コンストラクタ
     */
    public function __construct($frame_id)
    {
        // ログインしているユーザ
        $this->user = Auth::user();

        // 変数初期化
        $this->receive_views = ReceiveView::where('frame_id', $frame_id)->get();
        $this->receive_calcs = ReceiveCalc::where('frame_id', $frame_id)->orderBy('id', 'asc')->get();
        $this->receive_alerts = ReceiveAlert::where('frame_id', $frame_id)->orderBy('id', 'asc')->get();

        // 変数保持
        $this->frame_id = $frame_id;
    }

    /**
     *  登録件数の表示権限チェック
     */
    public function canViewCount()
    {
        return $this->canBase('total_count');
    }

    /**
     *  最終登録日時の表示権限チェック
     */
    public function canLastDate()
    {
        return $this->canBase('last_date');
    }

    /**
     *  最終登録内容の表示権限チェック
     */
    public function canLastData()
    {
        return $this->canBase('last_data');
    }

    /**
     *  集計(月毎)の表示権限チェック
     */
    public function canSum()
    {
        return $this->canBase('view_sum');
    }

    /**
     *  アラートの表示権限チェック
     */
    public function canAlert()
    {
        return $this->canBase('alert');
    }

    /**
     *  表示権限チェックの基礎
     */
    public function canBase($column_name)
    {
        // コンテンツ管理者はOK
        if ($this->user && $this->user->can('role_article_admin')) {
            return true;
        }

        // モデレータが表示OK＆自身がモデレータ権限を保有はOK
        if ($this->receive_views->where('role_name', 'article')->where($column_name, 1)->isNotEmpty() &&
            $this->user && 
            $this->user->can('role_article')) {
            return true;
        }
        // ゲストが表示OKは全員OK、以外はNG
        if ($this->receive_views->where('role_name', 'guest')->where($column_name, 1)->isNotEmpty()) {
            return true;
        }
        return false;
    }

    /**
     *  表示設定の取得
     */
    public function getViewSetting($role, $col_name)
    {
        // 表示設定がONか判定
        if ($this->receive_views->where($col_name, 1)->where('role_name', $role)->isNotEmpty()) {
            return true;
        }
        return false;
    }

    /**
     *  表示設定(月毎の集計設定)の取得
     */
    public function getReceiveCalc()
    {
        return $this->receive_calcs;
    }

    /**
     *  表示設定(アラート表示設定)の取得
     */
    public function geAlertSetting()
    {
        return $this->receive_alerts;
    }

    /**
     *  アラートの表示データの存在確認
     */
    public function hasAlert($receive_alerts)
    {
        if (empty($receive_alerts)) {
            return false;
        }
        foreach ($receive_alerts as $receive_alert) {
            if ($receive_alert->has('recs') && count($receive_alert['recs']) > 0) {
                return true;
            }
            return false;
        }
    }

    /**
     *  集計(月毎)データの取得
     */
    public function getReceiveSums($receive_frame)
    {
        // 集計(月毎)の収集データ取得
        // 設定の「月毎の集計設定」の項目を取得します。
        // データ自体は、1日に複数行あります。（10秒ごとに送られてくる可能性もあります）
        // ここでは、日ごとの平均を取得します。
        // 画面で表示する際は、月毎の積算か平均になります。
        // 表示の際は、Collectionの日ごとの平均もしくは積算をCollectionのメソッドで計算します。

        // 月毎の集計設定の項目数、ループする。
        $receive_sums = collect();
        foreach ($this->receive_calcs as $receive_calc) {
            $receive_datas = ReceiveData::select('receive_datas.date', 'receive_datas.month', \DB::raw("AVG(receive_datas.num_value) as num_value"))
                                 ->join('receive_records', 'receive_records.id', '=', 'receive_datas.record_id')
                                 ->join('receives', 'receives.id', '=', 'receive_records.receive_id')
                                 ->where('receives.id', $receive_frame->receive_id)
                                 ->where('receive_datas.column_key', $receive_calc->column) // temperature or humidity など
                                 ->groupBy('receive_datas.date')
                                 ->groupBy('receive_datas.month')
                                 ->orderBy('receive_datas.date', 'asc')
                                 ->get();

            // 月毎にグループ化（対象カラム, ）して画面に渡す。
            $receive_sums->push(collect([
                "column" => $receive_calc->column,
                "calc" => $receive_calc->calc,
                "sum_recs" => $receive_datas->groupBy('month')
            ]));
        }
        return $receive_sums;
    }

    /**
     *  アラートデータの取得
     */
    public function getReceiveAlert($receive_frame)
    {
        $receive_alerts = collect();

        // フレーム設定の receive_alert_column(アラートの項目)の数だけループする。
        foreach ($this->receive_alerts as $receive_alert) {
            $cond_time = date("Y-m-d H:i:s",strtotime("-" . $receive_alert->since . " second"));
            $receive_datas = ReceiveData::select('receive_datas.*')
                                 ->join('receive_records', 'receive_records.id', '=', 'receive_datas.record_id')
                                 ->join('receives', 'receives.id', '=', 'receive_records.receive_id')
                                 ->where('receives.id', $receive_frame->receive_id)
                                 ->where('receive_datas.column_key', $receive_alert->column) // temperature or humidity など
                                 ->where('receive_datas.created_at', '>', $cond_time)
                                 ->where("receive_datas.num_value", $receive_alert->condition, $receive_alert->value)
                                 ->orderBy('receive_datas.id', 'desc')
                                 ->get();

            // 画面に渡す。
            if ($receive_datas->isNotEmpty()) {
                $receive_alerts->push(
                    collect([
                        "alert"     => $receive_alert,
                        "recs"      => $receive_datas,
                ]));
            }
        }
        return $receive_alerts;
    }
}
