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
        @if(Auth::user()->role_id == 1)
            <div class="fixed left-0 bottom-[4%] bg-blue-500 rounded-r-full drop-shadow-md md:hidden overflow-hidden w-[75svw] h-12 pl-3">
                <!-- Background "Wait..." Text -->
                <p id="waitText" class="absolute inset-0 flex items-center justify-center text-white font-semibold text-lg opacity-0 transition-opacity duration-300">
                    Loading...
                </p>
            
                <!-- Swipe Area -->
                <div id="swipeArea" class="relative w-full h-full flex items-center overflow-hidden">
                    <!-- Draggable Slider -->
                    <div id="slider" class="absolute left-0 w-12 h-12 bg-white rounded-full flex items-center justify-center cursor-pointer touch-none transition-transform shadow-md">
                        <p><i class="ri-arrow-right-double-line font-extrabold text-2xl text-blue-500"></i></p>
                    </div>
                    <!-- Foreground Swipe Text -->
                    <p id="swipeText" class="text-white font-semibold text-lg text-center flex-1 select-none transition-opacity duration-300">
                        @if($latestBellow && $sppds->first())
                            {{ (optional($latestBellow[$sppds->first()->code_sppd] ?? null)->continue == 1) ? 'Lanjutkan SPPD' : 'Buat SPPD +' }}
                        @else
                            Buat SPPD +
                        @endif
                    </p>
                    <p id="arrow"><i class="ri-arrow-right-double-line text-white text-3xl pr-3 animate-pulse duration-300"></i></p>
                </div>
            </div>
        @endif
    </div>
    <script>
        var auth = @json(Auth::user()->role_id);
        var labelsDaily = @json($dates); // X-axis labels (dates in current month)
        var dataViews = @json($dataViews); // Viewable data per day
        var dataSppd = @json($dataSppd); // SPPD count per day

        var sppds = @json($sppds?->first()); // SPPD data
        var latestB = @json($latestBellow); // Latest B data
        // console.log(sppds, latestB, latestB[sppds.code_sppd].continue, sppds.code_sppd);
        

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

        const slider = document.getElementById("slider");
        const swipeArea = document.getElementById("swipeArea");
        const swipeText = document.getElementById("swipeText");
        const waitText = document.getElementById("waitText");
        const arrow = document.getElementById("arrow");
        let isDragging = false, startX = 0, currentX = 0, maxX = swipeArea.offsetWidth - slider.offsetWidth;

        const startDrag = (e) => {
            isDragging = true;
            startX = (e.touches ? e.touches[0].clientX : e.clientX) - slider.offsetLeft;
            slider.style.transition = "none";
            waitText.classList.remove("animate-pulse");
        };

        const onDrag = (e) => {
            if (!isDragging) return;
            currentX = (e.touches ? e.touches[0].clientX : e.clientX) - startX;
            currentX = Math.max(0, Math.min(maxX, currentX));
            slider.style.transform = `translateX(${currentX}px)`;

            // Show "Wait..." text when close to the end
            if (currentX >= maxX * 0.9) {
                swipeText.classList.add("opacity-0");
                arrow.classList.add("opacity-0");
                waitText.classList.remove("opacity-0");
                waitText.classList.add("animate-pulse");
            } else {
                swipeText.classList.remove("opacity-0");
                arrow.classList.remove("opacity-0");
                waitText.classList.add("opacity-0").remove("animate-pulse");
                waitText.classList.remove("animate-pulse");
            }
        };

        const stopDrag = () => {
            if (!isDragging) return;
            isDragging = false;
            if (currentX >= maxX * 0.9) {
                if (latestB?.[sppds?.[0]?.code_sppd]?.continue == 1) {
                    setTimeout(() => {
                        if (sppds?.[0]?.id) {
                            window.location.href = "{{ route('main_sppds.store-bottom', '0') }}".replace('0', sppds[0].id);
                        } else {
                            window.location.href = "{{ route('main_sppds.create') }}";
                        }
                    }, 500); // Small delay for UI feedback
                } else {
                    setTimeout(() => {
                        window.location.href = "{{ route('main_sppds.create') }}";
                    }, 500); // Small delay for UI feedback
                }
            } else {
                slider.style.transition = "transform 0.3s ease-out";
                slider.style.transform = "translateX(0)";
                swipeText.classList.remove("opacity-0");
                arrow.classList.remove("opacity-0");
                waitText.classList.add("opacity-0");
                waitText.classList.remove("animate-pulse");
            }
        };

        slider.addEventListener("mousedown", startDrag);
        slider.addEventListener("touchstart", startDrag);
        document.addEventListener("mousemove", onDrag);
        document.addEventListener("touchmove", onDrag);
        document.addEventListener("mouseup", stopDrag);
        document.addEventListener("touchend", stopDrag);
    </script>

</x-app-layout>
