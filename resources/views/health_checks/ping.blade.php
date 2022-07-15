<div>
    <canvas id="myChart"></canvas>

    @php

    $labels = '';
    $dataset = '';

    foreach ($datas as $data) {
        $labels .= '\''.$data['label'].'\',';
        $dataset .= ''.$data['value'].',';
    }

    @endphp
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = [{!! $labels !!}];
    const dataset = [{!! $dataset !!}];

    const data = {
        labels: labels,
        datasets: [{
            label: 'Ping (in ms)',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: dataset,
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
