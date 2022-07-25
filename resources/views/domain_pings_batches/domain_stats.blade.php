<x-guest-layout>
    <div>
        <h1 class="font-bold text-2xl">{{ $url }}</h1>
        <a class="text-blue-400 hover:underline" href="{{ url()->previous() }}">Retour</a>
    </div>
    <div>
        <canvas id="myChart"></canvas>

        @php

            $labels = '';
            $ping_data = '';

            foreach ($datas as $data) {
                $labels .= '\''.\Illuminate\Support\Carbon::createFromDate($data->created_at)->format('d M H:i').'\',';
                $ping_data .= ''.$data->answer_time_ms.',';
            }

        @endphp
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>

        const data = {
            labels: [{!! $labels !!}],
            datasets: [{
                label: 'Ping (in ms)',
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: [{!! $ping_data !!}],
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {}
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>

</x-guest-layout>
