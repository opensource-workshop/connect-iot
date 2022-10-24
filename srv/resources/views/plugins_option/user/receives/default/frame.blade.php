{{--
 * フレーム表示設定編集画面テンプレート。
 *
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category データ収集プラグイン
--}}
@extends('core.cms_frame_base_setting')

@section("core.cms_frame_edit_tab_$frame->id")
    {{-- プラグイン側のフレームメニュー --}}
    @include('plugins_option.user.receives.receives_frame_edit_tab')
@endsection

@section("plugin_setting_$frame->id")

{{-- 共通エラーメッセージ 呼び出し --}}
@include('plugins.common.errors_form_line')

@if (empty($buckets->id) && $action != 'createBuckets')
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-circle"></i>
        選択画面から、使用するバケツを選択するか、作成してください。
    </div>
@else
    {{-- 削除処理用フォーム(月毎の集計設定、アラート表示設定 共通) --}}
    <form action="" method="POST" name="delete_setting_form">
        {{ csrf_field() }}
        <input type="hidden" name="redirect_path" value="{{url('/')}}/plugin/receives/editRecieveView/{{$page->id}}/{{$frame_id}}#frame-{{$frame_id}}">
        <input type="hidden" name="calc_id" value="">
    </form>

    {{-- 月毎の集計設定の削除処理 --}}
    <script type="text/javascript">
        function submit_delete_sum(id) {
            if(confirm('集計設定を削除します。\nよろしいですか？')){
                delete_setting_form.action = "{{url('/')}}/redirect/plugin/receives/deleteRecieveCalc/{{$page->id}}/{{$frame_id}}/" + id + "#frame-{{$frame->id}}";
                delete_setting_form.submit();
            }
            return false;
        }
    </script>

    {{-- アラート表示設定の削除処理 --}}
    <script type="text/javascript">
        function submit_delete_alert(id) {
            if(confirm('アラート表示設定を削除します。\nよろしいですか？')){
                delete_setting_form.action = "{{url('/')}}/redirect/plugin/receives/deleteRecieveAlert/{{$page->id}}/{{$frame_id}}/" + id + "#frame-{{$frame->id}}";
                delete_setting_form.submit();
            }
            return false;
        }
    </script>

    <div class="alert alert-info">
        <i class="fas fa-exclamation-circle"></i>
        フレームごとの表示設定を変更します。
    </div>

    <form action="{{url('/')}}/redirect/plugin/receives/saveRecieveView/{{$page->id}}/{{$frame_id}}/{{$buckets->id}}#frame-{{$frame->id}}" method="POST" class="">
        {{ csrf_field() }}
        <input type="hidden" name="redirect_path" value="{{url('/')}}/plugin/receives/editRecieveView/{{$page->id}}/{{$frame_id}}/{{$buckets->bucket_id}}#frame-{{$frame_id}}">
        <input type="hidden" name="category" value="view_setting">

        <h5><span class="badge badge-primary">データ表示設定</span></h5>

        <div class="form-group">
            <table class="table">
                <tr>
                    <th></th>
                    <th>ゲスト</th>
                    <th>モデレータ</th>
                </tr>
                <tr>
                    <th>登録件数</th>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('guest', 'total_count'))
                                <input name="total_count_guest" value="1" type="checkbox" class="custom-control-input" id="total_count_guest" checked="checked">
                            @else
                                <input name="total_count_guest" value="1" type="checkbox" class="custom-control-input" id="total_count_guest">
                            @endif
                            <label class="custom-control-label" for="total_count_guest" id="label_total_count_guest"></label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('article', 'total_count'))
                                <input name="total_count_article" value="1" type="checkbox" class="custom-control-input" id="total_count_article" checked="checked">
                            @else
                                <input name="total_count_article" value="1" type="checkbox" class="custom-control-input" id="total_count_article">
                            @endif
                            <label class="custom-control-label" for="total_count_article" id="label_total_count_article"></label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>最終登録日時</th>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('guest', 'last_date'))
                                <input name="last_date_guest" value="1" type="checkbox" class="custom-control-input" id="last_date_guest" checked="checked">
                            @else
                                <input name="last_date_guest" value="1" type="checkbox" class="custom-control-input" id="last_date_guest">
                            @endif
                            <label class="custom-control-label" for="last_date_guest" id="label_last_date_guest"></label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('article', 'last_date'))
                                <input name="last_date_article" value="1" type="checkbox" class="custom-control-input" id="last_date_article" checked="checked">
                            @else
                                <input name="last_date_article" value="1" type="checkbox" class="custom-control-input" id="last_date_article">
                            @endif
                            <label class="custom-control-label" for="last_date_article" id="label_last_date_article"></label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>最終登録内容</th>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('guest', 'last_data'))
                                <input name="last_data_guest" value="1" type="checkbox" class="custom-control-input" id="last_data_guest" checked="checked">
                            @else
                                <input name="last_data_guest" value="1" type="checkbox" class="custom-control-input" id="last_data_guest">
                            @endif
                            <label class="custom-control-label" for="last_data_guest" id="label_last_data_guest"></label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('article', 'last_data'))
                                <input name="last_data_article" value="1" type="checkbox" class="custom-control-input" id="last_data_article" checked="checked">
                            @else
                                <input name="last_data_article" value="1" type="checkbox" class="custom-control-input" id="last_data_article">
                            @endif
                            <label class="custom-control-label" for="last_data_article" id="label_last_data_article"></label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>集計（月毎）</th>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('guest', 'sum'))
                                <input name="sum_guest" value="1" type="checkbox" class="custom-control-input" id="sum_guest" checked="checked">
                            @else
                                <input name="sum_guest" value="1" type="checkbox" class="custom-control-input" id="sum_guest">
                            @endif
                            <label class="custom-control-label" for="sum_guest" id="label_sum_guest"></label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('article', 'sum'))
                                <input name="sum_article" value="1" type="checkbox" class="custom-control-input" id="sum_article" checked="checked">
                            @else
                                <input name="sum_article" value="1" type="checkbox" class="custom-control-input" id="sum_article">
                            @endif
                            <label class="custom-control-label" for="sum_article" id="label_sum_article"></label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>アラート</th>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('guest', 'alert'))
                                <input name="alert_guest" value="1" type="checkbox" class="custom-control-input" id="alert_guest" checked="checked">
                            @else
                                <input name="alert_guest" value="1" type="checkbox" class="custom-control-input" id="alert_guest">
                            @endif
                            <label class="custom-control-label" for="alert_guest" id="label_alert_guest"></label>
                        </div>
                    </td>
                    <td>
                        <div class="custom-control custom-checkbox">
                            @if ($tool->getViewSetting('article', 'alert'))
                                <input name="alert_article" value="1" type="checkbox" class="custom-control-input" id="alert_article" checked="checked">
                            @else
                                <input name="alert_article" value="1" type="checkbox" class="custom-control-input" id="alert_article">
                            @endif
                            <label class="custom-control-label" for="alert_article" id="label_alert_article"></label>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Submitボタン --}}
        <div class="text-center">
            <a class="btn btn-secondary mr-2" href="{{URL::to($page->permanent_link)}}#frame-{{$frame->id}}">
                <i class="fas fa-times"></i><span class="{{$frame->getSettingButtonCaptionClass('md')}}"> キャンセル</span>
            </a>
            <button type="submit" class="btn btn-primary form-horizontal">
                <i class="fas fa-check"></i>
                <span class="{{$frame->getSettingButtonCaptionClass()}}">
                    変更確定
                </span>
            </button>
        </div>
    </form>

    <form action="{{url('/')}}/redirect/plugin/receives/saveRecieveCalc/{{$page->id}}/{{$frame_id}}/{{$buckets->id}}#frame-{{$frame->id}}" method="POST" class="">
        {{ csrf_field() }}
        <input type="hidden" name="redirect_path" value="{{url('/')}}/plugin/receives/editRecieveView/{{$page->id}}/{{$frame_id}}/{{$buckets->bucket_id}}#frame-{{$frame_id}}">
        <input type="hidden" name="category" value="sum_setting">

        <h5><span class="badge badge-primary">月毎の集計設定</span></h5>

        <div class="form-group">
            <table class="table table-bordered table-sm">
                <tr class="table-secondary">
                    <th>No</th>
                    <th>項目</th>
                    <th>計算</th>
                    <th>操作</th>
                </tr>
                @foreach ($tool->getReceiveCalc() as $calc_line)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$calc_line->column}}</td>
                    <td>{{$calc_line->calc}}</td>
                    <td><a href="javascript:submit_delete_sum({{$calc_line->id}});">削除</a></td>
                </tr>
                @endforeach
            </table>
        </div>

        <h5><span class="badge badge-secondary">日毎の集計設定追加</span></h5>

        <div class="form-group row">
            <label class="{{$frame->getSettingLabelClass()}}">項目</label>
            <div class="{{$frame->getSettingInputClass()}}">
                <select class="form-control" name="column">
                    @foreach(explode(',', $receive->columns) as $column)
                    <option value="{{$column}}">{{$column}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="{{$frame->getSettingLabelClass()}}">計算</label>
            <div class="{{$frame->getSettingInputClass()}}">
                <select class="form-control" name="calc">
                    <option value="addition">addition</option>
                    <option value="average">average</option>
                </select>
            </div>
        </div>

        {{-- Submitボタン --}}
        <div class="text-center">
            <a class="btn btn-secondary mr-2" href="{{URL::to($page->permanent_link)}}#frame-{{$frame->id}}">
                <i class="fas fa-times"></i><span class="{{$frame->getSettingButtonCaptionClass('md')}}"> キャンセル</span>
            </a>
            <button type="submit" class="btn btn-primary form-horizontal">
                <i class="fas fa-check"></i>
                <span class="{{$frame->getSettingButtonCaptionClass()}}">
                    追加
                </span>
            </button>
        </div>
    </form>

    <form action="{{url('/')}}/redirect/plugin/receives/saveRecieveAlert/{{$page->id}}/{{$frame_id}}/{{$buckets->id}}#frame-{{$frame->id}}" method="POST" class="">
        {{ csrf_field() }}
        <input type="hidden" name="redirect_path" value="{{url('/')}}/plugin/receives/editRecieveView/{{$page->id}}/{{$frame_id}}/{{$buckets->bucket_id}}#frame-{{$frame_id}}">
        <input type="hidden" name="category" value="alert_setting">

        <h5><span class="badge badge-primary">アラート表示設定</span></h5>

        <div class="form-group">
            <table class="table table-bordered table-sm">
                <tr class="table-secondary">
                    <th>No</th>
                    <th>項目</th>
                    <th>条件</th>
                    <th>値</th>
                    <th>期限</th>
                    <th>操作</th>
                </tr>
                @foreach ($tool->geAlertSetting() as $alert_line)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$alert_line->column}}</td>
                    <td>{{$alert_line->condition}}</td>
                    <td>{{$alert_line->value}}</td>
                    <td>{{$alert_line->since}}</td>
                    <td><a href="javascript:submit_delete_alert({{$alert_line->id}});">削除</a></td>
                </tr>
                @endforeach
            </table>
        </div>

        <h5><span class="badge badge-secondary">アラート表示設定追加</span></h5>

        <div class="form-group row">
            <label class="{{$frame->getSettingLabelClass()}}">項目</label>
            <div class="{{$frame->getSettingInputClass()}}">
                <select class="form-control" name="column">
                    @foreach(explode(',', $receive->columns) as $column)
                    <option value="{{$column}}">{{$column}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="{{$frame->getSettingLabelClass()}}">条件</label>
            <div class="{{$frame->getSettingInputClass()}}">
                <select class="form-control" name="condition">
                    <option value=">">＞</option>
                    <option value="=>">≧</option>
                    <option value="=">＝</option>
                    <option value="=<">≦</option>
                    <option value="<">＜</option>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label class="{{$frame->getSettingLabelClass()}}">値</label>
            <div class="{{$frame->getSettingInputClass()}}">
                <input type="text" name="value" value="" class="form-control">
            </div>
        </div>

        <div class="form-group row">
            <label class="{{$frame->getSettingLabelClass()}}">期限</label>
            <div class="{{$frame->getSettingInputClass()}}">
                <input type="text" name="since" value="" class="form-control">
            </div>
        </div>

        {{-- Submitボタン --}}
        <div class="text-center">
            <a class="btn btn-secondary mr-2" href="{{URL::to($page->permanent_link)}}#frame-{{$frame->id}}">
                <i class="fas fa-times"></i><span class="{{$frame->getSettingButtonCaptionClass('md')}}"> キャンセル</span>
            </a>
            <button type="submit" class="btn btn-primary form-horizontal">
                <i class="fas fa-check"></i>
                <span class="{{$frame->getSettingButtonCaptionClass()}}">
                    追加
                </span>
            </button>
        </div>
    </form>
@endif
@endsection
