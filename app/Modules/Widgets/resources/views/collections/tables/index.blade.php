<div class="widget-tables">
    @isset($data['title'])
        <p class="title">{{$data['title']}}</p>
    @endisset
    @if(isset($data['list']) && count($data['list']))
        <table style="width: 100%" class="table-desktop">
            <tbody>
            @foreach($data['list'] as $row)
                <tr>
                    @foreach($row as $item)
                        <td style="width: {{$item['column_width']}}">{!! $item['column_text'] !!}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($data['list-mob']) && count($data['list-mob']))
        <table style="width: 100%" class="table-mob">
            <tbody>
            @foreach($data['list-mob'] as $row)
                <tr>
                    @foreach($row as $item)
                        <td style="width: {{$item['column_width']}}">{!! $item['column_text'] !!}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
