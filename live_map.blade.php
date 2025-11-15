<div class="row">
    <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-3">
        <div class="card border mb-0">
            <div class="card-body p-0 position-relative">

                <style>
                    .live-map-wrapper {
                        position: relative;
                        width: 100%;
                        height: {{ $config['height'] }};
                    }

                    #map {
                        width: 100%;
                        height: 100%;
                        transition: filter 0.3s ease;
                    }

                    /* Dark map (CSS "night mode") */
                    .dark-map {
                        filter: brightness(0.5) contrast(1.2) saturate(1.1);
                    }

                    /* FLIGHT INFO CARD (TOP-RIGHT) */
                    .map-info-card-big {
                        position: absolute;
                        top: 20px;
                        right: 20px;
                        width: 260px;
                        background: #ffffff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        padding: 16px 18px;
                        z-index: 1000;
                        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.18);
                        font-size: 16px;
                        text-align: center;
                    }

                    .map-info-card-big hr {
                        margin: 12px 0;
                        border-top: 1px solid #eee;
                    }

                    .map-info-logo-big img {
                        max-width: 110px;
                        height: auto;
                        margin-bottom: 6px;
                    }

                    .map-info-route-big {
                        font-size: 22px;
                        font-weight: 700;
                        letter-spacing: 2px;
                    }

                    .map-info-row-big {
                        font-size: 16px;
                        padding: 4px 0;
                    }

                    /* STATUS BADGE */
                    .status-badge {
                        display: inline-block;
                        padding: 3px 12px;
                        border-radius: 999px;
                        font-size: 13px;
                        font-weight: 600;
                        letter-spacing: 0.03em;
                        background: #bdc3c7;
                        color: #ffffff;
                        text-transform: uppercase;
                    }

                    /* Boarding / planned */
                    .status-badge[data-status*="board" i],
                    .status-badge[data-status*="sched" i],
                    .status-badge[data-status*="pre-flight" i],
                    .status-badge[data-status*="preflight" i] {
                        background: #3498db;
                    }

                    /* Ground movement */
                    .status-badge[data-status*="push" i],
                    .status-badge[data-status*="taxi" i] {
                        background: #f39c12;
                    }

                    /* Airborne phases */
                    .status-badge[data-status*="takeoff" i],
                    .status-badge[data-status*="climb" i],
                    .status-badge[data-status*="cruise" i],
                    .status-badge[data-status*="descent" i],
                    .status-badge[data-status*="approach" i],
                    .status-badge[data-status*="enroute" i],
                    .status-badge[data-status*="in flight" i],
                    .status-badge[data-status*="airborne" i] {
                        background: #2ecc71;
                    }

                    /* Completed */
                    .status-badge[data-status*="arrived" i],
                    .status-badge[data-status*="landed" i],
                    .status-badge[data-status*="parked" i],
                    .status-badge[data-status*="completed" i] {
                        background: #16a085;
                    }

                    /* Abnormal */
                    .status-badge[data-status*="divert" i],
                    .status-badge[data-status*="cancel" i],
                    .status-badge[data-status*="abort" i],
                    .status-badge[data-status*="emerg" i] {
                        background: #e74c3c;
                    }

                    /* Paused / holding */
                    .status-badge[data-status*="pause" i],
                    .status-badge[data-status*="hold" i] {
                        background: #9b59b6;
                    }

                    

                    /* WEATHER BOX (BOTTOM-LEFT) */
                    .map-weather-box-left {
                        position: absolute;
                        bottom: 20px;
                        left: 20px;
                        width: 280px;
                        background: rgba(255,255,255,0.97);
                        border-radius: 10px;
                        padding: 8px 10px 6px;
                        z-index: 1100;
                        box-shadow: 0 3px 10px rgba(0,0,0,0.25);
                        border: 1px solid #ddd;
                    }

                    .map-weather-title {
                        font-size: 12px;
                        font-weight: 600;
                        text-transform: uppercase;
                        letter-spacing: 0.08em;
                        color: #777;
                        margin-bottom: 4px;
                        text-align: center;
                    }

                    .map-weather-buttons {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 6px;
                        margin-bottom: 4px;
                    }

                    .weather-btn {
                        flex: 1 0 30%;
                        min-width: 75px;
                        border-radius: 6px;
                        border: 1px solid #d0d0d0;
                        background: #ffffff;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        cursor: pointer;
                        padding: 4px 4px 2px;
                        font-size: 11px;
                        line-height: 1.2;
                        text-align: center;
                    }

                    .weather-btn i {
                        font-size: 17px;
                        color: #555;
                        margin-bottom: 2px;
                    }

                    .weather-btn span {
                        color: #666;
                    }

                    .weather-btn.active {
                        border-color: #2ecc71;
                        background: #e9f9f0;
                    }

                    .weather-btn.active i,
                    .weather-btn.active span {
                        color: #2ecc71;
                    }

                    .weather-slider-wrapper {
                        margin-top: 4px;
                        display: flex;
                        align-items: center;
                        gap: 6px;
                        font-size: 11px;
                        color: #555;
                    }

                    .weather-slider-wrapper input[type="range"] {
                        flex: 1;
                    }

                    /* Make OWM overlays clearly visible */
                    .owm-clouds-layer,
                    .owm-precip-layer,
                    .owm-storms-layer,
                    .owm-wind-layer,
                    .owm-temp-layer,
                    .owm-thunder-layer {
                        mix-blend-mode: multiply;
                        filter: contrast(3) saturate(4) brightness(0.8);
                    }

                    @media (max-width: 768px) {
                        .map-info-card-big {
                            right: 10px;
                            top: 10px;
                            width: 230px;
                        }
                        .map-weather-box-left {
                            left: 10px;
                            bottom: 10px;
                            width: 240px;
                        }
                    }
                </style>

                <div class="live-map-wrapper">
                    <div id="map"></div>

                    {{-- FLIGHT INFO (TOP-RIGHT) --}}
                    <div id="map-info-box" class="map-info-card-big" rv-show="pirep.id">
                        <div class="map-info-logo-big">
                            <img rv-src="pirep.airline.logo" alt="Airline Logo">
                        </div>

                        <div class="map-info-route-big">
                            { pirep.dpt_airport.icao }&nbsp; | &nbsp;{ pirep.arr_airport.icao }
                        </div>

                        <hr>

                        <div class="map-info-row-big">
                            <strong>{ pirep.airline.icao }{ pirep.flight_number }</strong>
                        </div>
                        <div class="map-info-row-big">
                            { pirep.aircraft.icao }&nbsp;{ pirep.aircraft.registration }
                        </div>
                        <div class="map-info-row-big">
                            { pirep.position.altitude } ft
                        </div>
                        <div class="map-info-row-big">
                            { pirep.position.gs } kts
                        </div>

                        
                        <div class="map-info-row-big">
                            Time flown: { pirep.flight_time | time_hm }
                        </div>

                       

                        <hr>

                        {{-- STATUS BADGE --}}
                        <div class="map-info-row-big">
                            <span class="status-badge"
                                  rv-text="pirep.status_text"
                                  rv-data-status="pirep.status_text"></span>
                        </div>
                    </div>

                    {{-- WEATHER BOX (BOTTOM-LEFT) --}}
                    <div class="map-weather-box-left">
                        <div class="map-weather-title">Weather Layers</div>

                        <div class="map-weather-buttons">
                            {{-- Row 1 --}}
                            <button id="btnClouds" type="button" class="weather-btn" title="Clouds">
                                <i class="fas fa-cloud"></i>
                                <span>Clouds</span>
                            </button>

                            <button id="btnRadar" type="button" class="weather-btn" title="Radar / Precipitation">
                                <i class="fas fa-cloud-sun-rain"></i>
                                <span>Radar</span>
                            </button>

                            <button id="btnStorms" type="button" class="weather-btn" title="Thunder / Storms">
                                <i class="fas fa-bolt"></i>
                                <span>Storms</span>
                            </button>

                            {{-- Row 2 --}}
                            <button id="btnWind" type="button" class="weather-btn" title="Wind">
                                <i class="fas fa-wind"></i>
                                <span>Wind</span>
                            </button>

                            <button id="btnTemp" type="button" class="weather-btn" title="Temperature">
                                <i class="fas fa-thermometer-half"></i>
                                <span>Temp</span>
                            </button>

                            <button id="btnCombined" type="button" class="weather-btn" title="Combined mode">
                                <i class="fas fa-layer-group"></i>
                                <span>Combo</span>
                            </button>

                            {{-- Row 3: Dark map --}}
                            <button id="btnDarkMap" type="button" class="weather-btn" title="Dark map"
                                    style="flex: 0 0 100%; max-width: 100%;">
                                <i class="fas fa-moon"></i>
                                <span>Dark map</span>
                            </button>
                        </div>

                        <div class="weather-slider-wrapper">
                            <span>Opacity</span>
                            <input type="range" id="weatherOpacity" min="0.2" max="1" step="0.05" value="1">
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>


@section('scripts')
    @parent

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('[LiveMap] DOMContentLoaded');

            // Rivets formatters
            if (typeof rivets !== 'undefined') {
                // store for fallback baseline
                window.liveMapProgress = {
                    initialRemaining: null
                };

                function nm(val) {
                    if (val == null) return NaN;
                    if (typeof val === 'object' && 'nmi' in val) {
                        var n = parseFloat(val.nmi);
                        return isNaN(n) ? NaN : n;
                    }
                    var n2 = parseFloat(val);
                    return isNaN(n2) ? NaN : n2;
                }

                // core helper: returns fraction 0–1 based on remaining + total
                function progressFraction(remaining, total) {
                    var rem = nm(remaining);
                    var tot = nm(total);
                    if (!rem || rem < 0 || isNaN(rem)) return 0;

                    // If we have a valid total, use it
                    if (tot && tot > 0 && !isNaN(tot)) {
                        var done = (tot - rem) / tot;
                        if (!isFinite(done)) done = 0;
                        return Math.max(0, Math.min(1, done));
                    }

                    // Fallback: dynamic baseline from remaining only
                    var store = window.liveMapProgress;
                    if (!store.initialRemaining || rem > store.initialRemaining) {
                        store.initialRemaining = rem;
                    }
                    var initial = store.initialRemaining;
                    var done2 = (initial - rem) / initial;
                    if (!isFinite(done2)) done2 = 0;
                    return Math.max(0, Math.min(1, done2));
                }

                // Remaining distance display
                rivets.formatters.to_go = function (remaining) {
                    var rem = nm(remaining);
                    if (!rem || rem < 0 || isNaN(rem)) return '—';
                    return Math.round(rem) + ' nmi';
                };

                // Progress 0–100% text
                rivets.formatters.progress_from_remaining = function (remaining, total) {
                    var frac = progressFraction(remaining, total);
                    return Math.round(frac * 100) + '%';
                };

                // Style for bar: width + color based on percentage
                rivets.formatters.progress_bar_style = function (remaining, total) {
                    var frac = progressFraction(remaining, total);
                    var pct = Math.round(frac * 100);

                    var color;
                    if (pct < 30) {
                        color = '#e74c3c'; // red
                    } else if (pct < 60) {
                        color = '#f39c12'; // orange
                    } else if (pct < 85) {
                        color = '#f1c40f'; // yellow
                    } else {
                        color = '#2ecc71'; // green
                    }

                    return 'width:' + pct + '%; background:' + color + ';';
                };

                // Style for circular progress (conic-gradient)
                rivets.formatters.progress_circle_style = function (remaining, total) {
                    var frac = progressFraction(remaining, total);
                    var pct = Math.round(frac * 100);

                    var color;
                    if (pct < 30) {
                        color = '#e74c3c';
                    } else if (pct < 60) {
                        color = '#f39c12';
                    } else if (pct < 85) {
                        color = '#f1c40f';
                    } else {
                        color = '#2ecc71';
                    }

                    return 'background: conic-gradient(' + color + ' 0 ' + pct +
                        '%, #e5e5e5 ' + pct + '% 100%);';
                };

                // Remaining time from remaining + GS
                rivets.formatters.rem_time_from_remaining = function (remaining, gs) {
                    var rem = nm(remaining);
                    var speed = parseFloat(gs);
                    if (!rem || rem <= 0 || !speed || speed <= 0) return '—';

                    var hours = rem / speed;
                    var mins = Math.round(hours * 60);
                    var h = Math.floor(mins / 60);
                    var m = mins % 60;
                    if (h <= 0) return m + 'm';
                    return h + 'h ' + (m < 10 ? '0' + m : m) + 'm';
                };

                // ETA local from remaining + GS
                rivets.formatters.eta_from_remaining = function (remaining, gs) {
                    var rem = nm(remaining);
                    var speed = parseFloat(gs);
                    if (!rem || rem <= 0 || !speed || speed <= 0) return '—';

                    var hours = rem / speed;
                    var ms = hours * 3600000;
                    var now = new Date();
                    var eta = new Date(now.getTime() + ms);
                    var hh = eta.getHours().toString().padStart(2, '0');
                    var mm = eta.getMinutes().toString().padStart(2, '0');
                    return hh + ':' + mm;
                };
            }

            function attachWeatherToMap(map) {
                console.log('[LiveMap] attachWeatherToMap called, map:', map);

                // 👉 PUT YOUR REAL OWM API KEY HERE
                var OWM_API_KEY = "YOUR_OPENWEATHERMAP_API_KEY_HERE";

                if (!OWM_API_KEY || OWM_API_KEY === "YOUR_OPENWEATHERMAP_API_KEY_HERE") {
                    console.warn('[LiveMap] OWM API key not set; skipping overlays');
                    return;
                }

                // Create a dedicated pane for all weather overlays
                var weatherPane = map.getPane('weatherPane');
                if (!weatherPane) {
                    map.createPane('weatherPane');
                    weatherPane = map.getPane('weatherPane');
                }
                weatherPane.style.zIndex = 650;
                weatherPane.style.pointerEvents = 'none';

                // --- OWM layers ---
                var cloudsLayer = L.tileLayer(
                    "https://tile.openweathermap.org/map/clouds_new/{z}/{x}/{y}.png?appid=" + OWM_API_KEY,
                    {
                        opacity: 1.0,
                        pane: 'weatherPane',
                        className: 'owm-clouds-layer',
                        attribution: "Clouds © OpenWeatherMap"
                    }
                );

                var precipLayer = L.tileLayer(
                    "https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid=" + OWM_API_KEY,
                    {
                        opacity: 1.0,
                        pane: 'weatherPane',
                        className: 'owm-precip-layer',
                        attribution: "Precipitation © OpenWeatherMap"
                    }
                );

                var stormsLayer = L.tileLayer(
                    "https://tile.openweathermap.org/map/thunder_new/{z}/{x}/{y}.png?appid=" + OWM_API_KEY,
                    {
                        opacity: 1.0,
                        pane: 'weatherPane',
                        className: 'owm-thunder-layer owm-storms-layer',
                        attribution: "Thunderstorms © OpenWeatherMap"
                    }
                );

                var windLayer = L.tileLayer(
                    "https://tile.openweathermap.org/map/wind_new/{z}/{x}/{y}.png?appid=" + OWM_API_KEY,
                    {
                        opacity: 1.0,
                        pane: 'weatherPane',
                        className: 'owm-wind-layer',
                        attribution: "Wind © OpenWeatherMap"
                    }
                );

                var tempLayer = L.tileLayer(
                    "https://tile.openweathermap.org/map/temp_new/{z}/{x}/{y}.png?appid=" + OWM_API_KEY,
                    {
                        opacity: 1.0,
                        pane: 'weatherPane',
                        className: 'owm-temp-layer',
                        attribution: "Temperature © OpenWeatherMap"
                    }
                );

                // Add Radar/precip by default
                precipLayer.addTo(map);

                // Buttons
                var btnClouds   = document.getElementById("btnClouds");
                var btnRadar    = document.getElementById("btnRadar");
                var btnStorms   = document.getElementById("btnStorms");
                var btnWind     = document.getElementById("btnWind");
                var btnTemp     = document.getElementById("btnTemp");
                var btnCombined = document.getElementById("btnCombined");
                var btnDarkMap  = document.getElementById("btnDarkMap");
                var opacitySlider = document.getElementById("weatherOpacity");
                var mapDiv = document.getElementById("map");

                if (!btnClouds || !btnRadar || !btnStorms || !btnWind || !btnTemp || !btnCombined || !btnDarkMap) {
                    console.error('[LiveMap] Weather buttons not found in DOM');
                    return;
                }

                // Track button states
                btnClouds._on   = false;
                btnRadar._on    = true;
                btnStorms._on   = false;
                btnWind._on     = false;
                btnTemp._on     = false;

                btnRadar.classList.add("active");

                var allLayers = [cloudsLayer, precipLayer, stormsLayer, windLayer, tempLayer];

                function setAllWeatherOpacity(op) {
                    allLayers.forEach(function (layer) {
                        if (layer.setOpacity) {
                            layer.setOpacity(op);
                        }
                    });
                }

                function toggleLayer(btn, layer) {
                    if (!layer) return;

                    if (btn._on) {
                        map.removeLayer(layer);
                        btn.classList.remove("active");
                    } else {
                        layer.addTo(map);
                        btn.classList.add("active");
                    }
                    btn._on = !btn._on;
                }

                // Button handlers
                btnClouds.addEventListener("click", function () {
                    toggleLayer(btnClouds, cloudsLayer);
                });

                btnRadar.addEventListener("click", function () {
                    toggleLayer(btnRadar, precipLayer);
                });

                btnStorms.addEventListener("click", function () {
                    toggleLayer(btnStorms, stormsLayer);
                });

                btnWind.addEventListener("click", function () {
                    toggleLayer(btnWind, windLayer);
                });

                btnTemp.addEventListener("click", function () {
                    toggleLayer(btnTemp, tempLayer);
                });

                // Combined mode: Clouds + Radar + Thunder ON
                btnCombined.addEventListener("click", function () {
                    if (!btnClouds._on) {
                        cloudsLayer.addTo(map);
                        btnClouds._on = true;
                        btnClouds.classList.add("active");
                    }
                    if (!btnRadar._on) {
                        precipLayer.addTo(map);
                        btnRadar._on = true;
                        btnRadar.classList.add("active");
                    }
                    if (!btnStorms._on) {
                        stormsLayer.addTo(map);
                        btnStorms._on = true;
                        btnStorms.classList.add("active");
                    }
                });

                // Dark map toggle (CSS filter)
                btnDarkMap.addEventListener("click", function () {
                    var dark = mapDiv.classList.toggle("dark-map");
                    if (dark) {
                        btnDarkMap.classList.add("active");
                    } else {
                        btnDarkMap.classList.remove("active");
                    }
                });

                // Opacity slider
                opacitySlider.addEventListener("input", function () {
                    var op = parseFloat(this.value);
                    setAllWeatherOpacity(op);
                });
            }

            // Register Leaflet init hook
            if (typeof L !== 'undefined' && L.Map && typeof L.Map.addInitHook === 'function') {
                console.log('[LiveMap] Registering Leaflet init hook for OWM');
                L.Map.addInitHook(function () {
                    attachWeatherToMap(this);
                });
            } else {
                console.error('[LiveMap] Leaflet not loaded; cannot register weather hook');
            }

            // Render phpVMS live map
            if (!window.phpvms || !phpvms.map || typeof phpvms.map.render_live_map !== 'function') {
                console.error('[LiveMap] phpvms.map helper not available; cannot init live map');
                return;
            }

            console.log('[LiveMap] Calling phpvms.map.render_live_map');

            phpvms.map.render_live_map({
                center: ['{{ $center[0] }}', '{{ $center[1] }}'],
                zoom: '{{ $zoom }}',
                aircraft_icon: '{!! public_asset('/assets/img/acars/aircraft.png') !!}',
                refresh_interval: {{ setting('acars.update_interval', 60) }},
                units: '{{ setting('units.distance ') }}',
                flown_route_color: '#db2433',
                leafletOptions: {
                    scrollWheelZoom: true,
                    providers: {
                        'CartoDB.Positron': {},
                    }
                }
            });
        });
    </script>
@endsection
