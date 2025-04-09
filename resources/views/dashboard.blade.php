<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    {!! $htmlContent !!}
    <div class="hidden md:block">
        <x-footer />
    </div>
    @if (Auth::user()->role_id == 1)
            <div id="swipeArea" class="fixed z-1 left-0 right-0 bottom-[9%] flex justify-center items-end gap-0.5 w-full">
                <div id="mainSlide"
                    class="w-1/2 bg-gradient-to-br from-blue-500 via-blue-400 to-blue-300 rounded-t-4xl drop-shadow-md md:hidden overflow-hidden flex items-center justify-center"
                    style="height: 3rem;">
                    <span id="slideBtn"
                        class="flex items-center justify-center gap-2 text-white transition-all duration-300 ease-in-out">
                        <i class="ri-arrow-up-double-fill text-3xl"></i>
                        <p class="font-semibold">Buat SPPD +</p>
                        <i class="ri-arrow-up-double-fill text-3xl"></i>
                    </span>
                </div>
                <div id="mainSlide2"
                    class="w-1/2 bg-gradient-to-bl from-blue-500 via-blue-400 to-blue-300 rounded-t-4xl drop-shadow-md md:hidden overflow-hidden flex items-center justify-center"
                    style="height: 3rem;">
                    <span id="slideBtn2"
                        class="flex items-center justify-center gap-2 text-white transition-all duration-300 ease-in-out">
                        <i class="ri-arrow-up-double-fill text-3xl"></i>
                        <p class="font-semibold">Buat SPPD +</p>
                        <i class="ri-arrow-up-double-fill text-3xl"></i>
                    </span>
                </div>
            </div>
        </div>
    @endif
    </div>
    <script defer>
        $(document).ready(function() {
            var auth = @json(Auth::user()->role_id);
            var authId = @json(Auth::user()->id);
            var labelsDaily = @json($dates); // X-axis labels (dates in current month)
            var dataViews = @json($dataViews); // Viewable data per day
            var dataSppd = @json($dataSppd); // SPPD count per day

            var sppds = @json($sppds?->first()); // SPPD data
            var sppdsAll = @json($sppds); // SPPD data
            var latestB = @json($latestBellow); // Latest B data
            var latestB2 = @json($latestBellow->groupBy('code_sppd')->values())

            const mainSlider = document.getElementById("mainSlide");
            const mainSlider2 = document.getElementById("mainSlide2");
            const slideBtn = document.getElementById("slideBtn");
            const slideBtn2 = document.getElementById("slideBtn2");

            var item = latestB2?.[0]?.[0];

            var notVerified = item?.main_sppd?.user_id === authId &&
                            item?.main_sppd?.verify === 0;

            var verified = item?.main_sppd?.user_id === authId &&
                        item?.continue === 1 &&
                        latestB2?.[0]?.length === 1;

            var nextStep = item?.main_sppd?.user_id === authId &&
                        item?.continue === 1 &&
                        latestB2?.[0]?.length > 1;
        
            // console.log(latestB2);
            
            if (notVerified) {
                mainSlider2.classList.add("hidden");
                mainSlider.classList.remove("w-1/2");
                mainSlider.classList.add("h-12", "w-full");
                slideBtn.innerHTML = `
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                    <p class="font-semibold">Edit SPPD</p>
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                `;
            } else if (verified) {
                mainSlider2.classList.add("hidden");
                mainSlider.classList.remove("w-1/2");
                mainSlider.classList.add("h-12", "w-full");
                slideBtn.innerHTML = `
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                    <p class="font-semibold">Verifikasi Kedatangan</p>
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                `;
            } else if (nextStep) {
                [mainSlider, mainSlider2].forEach((el, i) => {
                    el.classList.remove("rounded-t-4xl");
                    el.classList.add("h-12", i === 0 ? "rounded-tl-4xl" : "rounded-tr-4xl");
                });
                slideBtn.innerHTML = `
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                    <p class="font-semibold">Pulang</p>
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                `;
                slideBtn2.innerHTML = `
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                    <p class="font-semibold">Lanjut</p>
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                `;

            } else {
                mainSlider2.classList.add("hidden");
                mainSlider.classList.remove("w-1/2");
                mainSlider.classList.add("h-36", "w-full");
                slideBtn.innerHTML = `
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                    <p class="font-semibold">Buat SPPD +</p>
                    <i class="ri-arrow-up-double-fill text-3xl"></i>
                `;
                
            }
        
            let startY = 0;
            let currentY = 0;
            let dragging = false;
            const maxHeight = 140; // h-36
            const minHeight = 48;  // h-12
            const triggerHeight = 91;
        
            mainSlide.addEventListener("touchstart", (e) => {
                startY = e.touches[0].clientY;
                dragging = true;
        
                // Disable transition while dragging
                mainSlide.style.transition = "none";
            });

            mainSlider2.addEventListener("touchstart", (e) => {
                startY = e.touches[0].clientY;
                dragging = true;
        
                // Disable transition while dragging
                mainSlider2.style.transition = "none";
            });
        
            mainSlide.addEventListener("touchmove", (e) => {
                if (!dragging) return;
        
                currentY = e.touches[0].clientY;
                const delta = startY - currentY;
        
                let newHeight = mainSlide.offsetHeight + delta;
        
                // Clamp between min and max height
                newHeight = Math.max(minHeight, Math.min(maxHeight, newHeight));
        
                mainSlide.style.height = `${newHeight}px`;
        
                // Reset startY to current for smooth dragging
                startY = currentY;
            });

            mainSlider2.addEventListener("touchmove", (e) => {
                if (!dragging) return;
        
                currentY = e.touches[0].clientY;
                const delta = startY - currentY;
        
                let newHeight = mainSlider2.offsetHeight + delta;
        
                // Clamp between min and max height
                newHeight = Math.max(minHeight, Math.min(maxHeight, newHeight));
        
                mainSlider2.style.height = `${newHeight}px`;
        
                // Reset startY to current for smooth dragging
                startY = currentY;
            });
        
            mainSlide.addEventListener("touchend", () => {
                dragging = false;
                const currentHeight = parseInt(mainSlide.style.height);
        
                // Enable transition back for snapping
                mainSlide.style.transition = "height 0.3s ease-in-out";
        
                if (currentHeight > minHeight + triggerHeight) {
                    // Expand fully
                    mainSlide.style.height = `${maxHeight}px`;
                    slideBtn.innerHTML = `
                        <i class="ri-loader-4-fill animate-spin text-3xl text-white"></i>
                        <p class="font-semibold text-white">Wait...</p>
                    `;
        
                    setTimeout(() => {
                        if (notVerified) {
                            window.location.href = "{{ route('main_sppds.change', 'null') }}".replace('null', sppds?.id);
                        } else if (verified) {
                            window.location.href = "{{ route('main_sppds.bottom', 'null') }}?continue=VERIFIKASI"
                            .replace('null', sppds?.code_sppd);
                        } else if (nextStep) {
                            window.location.href = "{{ route('main_sppds.bottom', 'null') }}"
                            .replace('null', sppds?.code_sppd) + "?continue=false";
                        } else {
                            // Directly go to create route if lbLength is 0
                            window.location.href = "{{ route('main_sppds.create') }}";
                            
                        }
                    }, 500);
                } else {
                    // Snap back to closed
                    mainSlide.style.height = `${minHeight}px`;
                }
            });

            mainSlider2.addEventListener("touchend", () => {
                dragging = false;
                const currentHeight = parseInt(mainSlider2.style.height);
        
                // Enable transition back for snapping
                mainSlider2.style.transition = "height 0.3s ease-in-out";
        
                if (currentHeight > minHeight + triggerHeight) {
                    // Expand fully
                    mainSlider2.style.height = `${maxHeight}px`;
                    slideBtn2.innerHTML = `
                        <i class="ri-loader-4-fill animate-spin text-3xl text-white"></i>
                        <p class="font-semibold text-white">Wait...</p>
                    `;
        
                    setTimeout(() => {
                            window.location.href = "{{ route('main_sppds.bottom', 'null') }}"
                            .replace('null', sppds?.code_sppd) + "?continue=true";
                    }, 500);
                } else {
                    // Snap back to closed
                    mainSlider2.style.height = `${minHeight}px`;
                }
            });
            // For desktop view
            if (auth == 2) {
                $(document).ready(function() {
                    var ctx = document.getElementById('dailyChart').getContext('2d');

                    var datasets = [{
                        label: 'SPPD Entries (Daily)',
                        data: dataSppd,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }];

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
                                title: {
                                    display: true,
                                    text: 'Date (Current Month)'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Count'
                                },
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
        });
    </script>

</x-app-layout>
