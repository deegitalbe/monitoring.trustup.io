<div>
    <canvas id="myChart"></canvas>

    @php

    $labels = '';
    $ping_data = '';

    foreach ($datas as $data) {
        $labels .= '\''.$data['label'].'\',';
        $ping_data .= ''.$data['value'].',';
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
