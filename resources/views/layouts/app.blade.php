<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SPPD Online - PT. Surya Amanah Cendikia</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Styles -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('jquery/notify.js')}}"></script>
    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 relative">
        @include('components.navbar')
        <div class="pt-14 pb-10 gap-4 lg:flex w-auto">
            <x-sidebar/>
            <div id="right-side" class="lg:w-5/6 w-auto lg:ml-[16.666667svw]">
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl text-start mx-auto py-4 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center" x-data="clock()">
                            {{ $header }}
                            <p class="text-xs">
                                <i :class="weatherIcon" class="ri-lg"></i>
                                <span x-text="weather"></span>
                            </p>
                            <div class="flex md:block">
                                <p x-text="time + ' ' + zone + (window.innerWidth <= 768 ? ', ' : '')" class="text-end md:text-sm text-xs"></p>
                                <p x-text="date" class="text-xs"></p>
                            </div>
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
        <x-mobile-nav/>
    </div>
    <script>
        function clock() {
            return {
                time: '',
                zone: '',
                date: '',
                weather: '',
                weatherIcon: '',
                init() {
                    this.updateTime();
                    this.getLocationAndFetchWeather();
                    setInterval(() => {
                        this.updateTime();
                    }, 1000);
                },
                updateTime() {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    const timeZone = this.getTimeZone(now);
                    const dateOptions = { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' };
                    const dateString = now.toLocaleDateString('id-ID', dateOptions);
                    this.time = `${hours}:${minutes}:${seconds}`;
                    this.zone = `${timeZone}`;
                    this.date = `${dateString}`;
                },
                getTimeZone(date) {
                    const offset = date.getTimezoneOffset() / -60;
                    if (offset === 7) return 'WIB';
                    if (offset === 8) return 'WITA';
                    if (offset === 9) return 'WIT';
                    return '';
                },
                getLocationAndFetchWeather() {
                    if (navigator.geolocation) {
                        navigator.geolocation.watchPosition(position => {
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;
                            this.fetchWeather(latitude, longitude);
                        }, error => {
                            console.error('Error getting location:', error);
                            // Fallback to a default location if user denies location access
                            this.fetchWeather(-6.2, 106.8); // Default to Jakarta
                        });
                    } else {
                        console.error('Geolocation is not supported by this browser.');
                        // Fallback to a default location if geolocation is not supported
                        this.fetchWeather(-6.2, 106.8); // Default to Jakarta
                    }
                },
                fetchWeather(latitude, longitude) {
                    const apiUrl = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true`;

                    fetch(apiUrl)
                        .then(response => response.json())
                        .then(data => {
                            const temperature = data.current_weather.temperature;
                            const weatherCode = data.current_weather.weathercode;
                            const weatherName = this.getWeatherName(weatherCode);
                            this.weather = `Suhu: ${temperature}°C, Cuaca: ${weatherName}`;
                            this.weatherIcon = this.getWeatherIcon(weatherCode);
                        })
                        .catch(error => {
                            console.error('Error fetching weather data:', error);
                        });
                },
                getWeatherName(code) {
                    const weatherCodes = {
                        0: 'Langit cerah',
                        1: 'Cerah berawan',
                        2: 'Sebagian berawan',
                        3: 'Mendung',
                        45: 'Kabut',
                        48: 'Kabut rime',
                        51: 'Gerimis: Ringan',
                        53: 'Gerimis: Sedang',
                        55: 'Gerimis: Lebat',
                        56: 'Gerimis beku: Ringan',
                        57: 'Gerimis beku: Lebat',
                        61: 'Hujan: Ringan',
                        63: 'Hujan: Sedang',
                        65: 'Hujan: Lebat',
                        66: 'Hujan beku: Ringan',
                        67: 'Hujan beku: Lebat',
                        71: 'Salju: Ringan',
                        73: 'Salju: Sedang',
                        75: 'Salju: Lebat',
                        77: 'Butiran salju',
                        80: 'Hujan deras: Ringan',
                        81: 'Hujan deras: Sedang',
                        82: 'Hujan deras: Lebat',
                        85: 'Hujan salju: Ringan',
                        86: 'Hujan salju: Lebat',
                        95: 'Badai petir: Ringan atau sedang',
                        96: 'Badai petir dengan hujan es ringan',
                        99: 'Badai petir dengan hujan es lebat'
                    };
                    return weatherCodes[code] || 'Tidak diketahui';
                },
                getWeatherIcon(code) {
                    const weatherIcons = {
                        0: 'ri-sun-line',
                        1: 'ri-sun-cloudy-line',
                        2: 'ri-cloudy-line',
                        3: 'ri-cloudy-fill',
                        45: 'ri-foggy-line',
                        48: 'ri-foggy-line',
                        51: 'ri-drizzle-line',
                        53: 'ri-drizzle-line',
                        55: 'ri-drizzle-line',
                        56: 'ri-drizzle-line',
                        57: 'ri-drizzle-line',
                        61: 'ri-rainy-line',
                        63: 'ri-rainy-line',
                        65: 'ri-rainy-fill',
                        66: 'ri-rainy-line',
                        67: 'ri-rainy-fill',
                        71: 'ri-snowy-line',
                        73: 'ri-snowy-line',
                        75: 'ri-snowy-fill',
                        77: 'ri-snowy-line',
                        80: 'ri-showers-line',
                        81: 'ri-showers-line',
                        82: 'ri-showers-fill',
                        85: 'ri-snowy-line',
                        86: 'ri-snowy-fill',
                        95: 'ri-thunderstorms-line',
                        96: 'ri-thunderstorms-line',
                        99: 'ri-thunderstorms-fill'
                    };
                    return weatherIcons[code] || 'ri-question-line';
                }
            };
        }

        document.addEventListener("DOMContentLoaded", function () {
            const scrollbarStyle = document.createElement("style");
            document.head.appendChild(scrollbarStyle);

            function updateScrollbar() {
                if (window.scrollY === 0) {
                    scrollbarStyle.innerHTML = `
                        ::-webkit-scrollbar-thumb:vertical {
                            border-radius: 0 0 10px 10px; /* Remove top radius only for vertical scrollbar */
                        }
                    `;
                } else if (window.scrollY + window.innerHeight === document.documentElement.scrollHeight) {
                    scrollbarStyle.innerHTML = `
                        ::-webkit-scrollbar-thumb:vertical {
                            border-radius: 10px 10px 0 0; /* Remove bottom radius only for vertical scrollbar */
                        }
                    `;
                } else {
                    scrollbarStyle.innerHTML = `
                        ::-webkit-scrollbar-thumb:vertical {
                            border-radius: 10px; /* Restore full radius for vertical scrollbar */
                        }
                    `;
                }
            }

            window.addEventListener("scroll", updateScrollbar);
            updateScrollbar(); // Run on page load
        });
    </script>
</body>

</html>
