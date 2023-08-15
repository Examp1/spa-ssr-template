<div class="section-table">
    @if (!empty($content['anker_title']))
        <div class="title">{{ $content['anker_title'] }}</div>
    @endif

    @if(isset($content['rows']) && count($content['rows']))
        <table style="width: 100%" class="table-desktop">
            <tbody>
                @foreach($content['rows'] as $row)
                    <tr>
                        @foreach($row as $item)
                            <td {{--style="width: {{$item['column_width']}}"--}}>{{--{!! $item['column_text'] !!}--}}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($content['mob_rows']) && count($content['mob_rows']))
        <table style="width: 100%" class="table-mob">
            <tbody>
            @foreach($content['mob_rows'] as $row)
                <tr>
                    @foreach($row as $item)
                        <td {{--style="width: {{$item['column_width']}}"--}}>{{--{!! $item['column_text'] !!}--}}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
