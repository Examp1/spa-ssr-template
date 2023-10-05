<div class="row ml-3 mr-3">
    <div class="col-sm-12">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">{{ __('Date/time') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($order->historyList() as $history)
                {{-- @dd($history)  --}}
                    <tr>
                        <th scope="row">{{ $history['time'] }}</th>
                        <td>{{ $history['text'] }}</td>
                    </tr>
                @empty

                @endforelse
            </tbody>
        </table>
    </div>
</div>
