<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    {!! $htmlContent !!}
        <div class="hidden md:block">
            <x-footer/>
        </div>
    </div>
    <script>
        var auth = @json(Auth::user()->role_id);
        var labelsDaily = @json($dates); // X-axis labels (dates in current month)
        var dataViews = @json($dataViews); // Viewable data per day
        var dataSppd = @json($dataSppd); // SPPD count per day

        if (auth == 2) {
            $(document).ready(function () {
                var ctx = document.getElementById('dailyChart').getContext('2d');

                var datasets = [
                    {
                        label: 'SPPD Entries (Daily)',
                        data: dataSppd,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }
                ];

                // Conditionally add the "Total Viewable" dataset if auth == 2
                auth == 2 && datasets.push({
                    label: 'Total Viewable (Daily)',
                    data: dataViews,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                });

                var chartData = {
                    labels: labelsDaily,
                    datasets: datasets
                };

                var chartOptions = {
                    responsive: true,
                    scales: {
                        x: {
                            title: { display: true, text: 'Date (Current Month)' }
                        },
                        y: {
                            title: { display: true, text: 'Count' },
                            beginAtZero: true,
                            suggestedMin: 0
                        }
                    }
                };

                new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: chartOptions
                });
            });
        }
    </script>

</x-app-layout>
