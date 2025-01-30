<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="w-1/2 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-sm">
                <div class="m-3">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script>
       var labelsMonthly = @json($labelsMonthly);
       var dataMonthly = @json($dataMonthly);

       // Grafik Mingguan
       var ctxWeekly = document.getElementById('monthlyChart').getContext('2d');
        var monthlyChart = new Chart(ctxWeekly, {
            type: 'line',
            data: {
                labels: labelsMonthly,
                datasets: [{
                    label: 'Total Viewable Monthly',
                    data: dataMonthly,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        title: {
                            display: true,
                            text: 'Total Viewable'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
      </script>
</x-app-layout>
