{{--
 * データ収集画面テンプレート。
 *
 * @author 永原　篤 <nagahara@opensource-workshop.jp>
 * @copyright OpenSource-WorkShop Co.,Ltd. All Rights Reserved
 * @category データ収集プラグイン
 --}}
@extends('core.cms_frame_base')

@section("plugin_contents_$frame->id")

<dl class="row">
{{-- 登録件数 --}}
@if ($receives_tool->canViewCount())
    <dt class="col-sm-2">登録件数</dt>
    <dd class="col-sm-10">{{$receives_count}}件</dd>
@endif

{{-- 最終登録日時 --}}
@if ($receives_tool->canLastDate())
    <dt class="col-sm-2">最終登録日時</dt>
    <dd class="col-sm-10">@isset($last_date){{$last_date->created_at}}@endif</dd>
@endif

{{-- 最終登録内容 --}}
@if ($receives_tool->canLastData())
    <dt class="col-sm-2">最終登録内容</dt>
    <dd class="col-sm-10">
        @isset($last_data)
            @foreach ($last_data as $last_item)
                {{$last_item->column_key}} : {{$last_item->value}}<br />
            @endforeach
        @endif
    </dd>
@endif

{{-- 集計(月毎) --}}
@if ($receives_tool->canSum())
    <dt class="col-sm-2">集計(月毎)</dt>
    <dd class="col-sm-10">
    @foreach($receive_sums as $receive_sum)
        {{$receive_sum["column"]}} - {{$receive_sum["calc"]}}<br />
        <table class="table table-sm table-bordered">
        <tr class="table-secondary"><th>月</th><th>計算結果</th></tr>
        @foreach($receive_sum["sum_recs"] as $month => $days)
            <tr>
                <td>{{$month}}</td>
                <td>
                @if ($receive_sum["calc"] == 'addition')
                    {{round($days->sum('num_value'), 2)}}
                @else
                    {{round($days->avg('num_value'), 2, PHP_ROUND_HALF_UP)}}
                @endif
                </td>
            </tr>
        @endforeach
        </table>
    @endforeach
    </dd>
@endif

{{-- アラート --}}
@if ($receives_tool->canAlert() && $receives_tool->hasAlert($receive_alerts))
    <dt class="col-sm-2 text-danger">アラート</dt>
    <dd class="col-sm-10 text-danger">
    @foreach($receive_alerts as $receive_alert)
        {{$receive_alert["alert"]->column}} {{$receive_alert["alert"]->condition}} {{$receive_alert["alert"]->value}} ({{$receive_alert["alert"]->since}}秒以内)<br />
        <table class="table table-sm table-bordered">
        <tr class="table-secondary"><th>値</th><th>日時</th></tr>
        @foreach($receive_alert["recs"] as $rec)
            <tr>
                <td>{{$rec->value}}</td>
                <td>{{$rec->created_at}}</td>
            </tr>
        @endforeach
        </table>
    @endforeach
    </dd>
@endif
</dl>

<hr />
{{-- モデレータ（他ユーザの記事も更新）の場合のみ、表示 --}}
@can("role_article")

    <form action="{{url('/')}}/download/plugin/receives/downloadCsv/{{$page->id}}/{{$frame_id}}/{{$receive_frame->receive_id}}" method="POST" class="">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-success btn-sm">
            <i class="fas fa-file-download"></i> ダウンロード
        </button>
    </form>
@endcan

@endsection
