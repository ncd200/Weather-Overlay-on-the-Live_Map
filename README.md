# Advanced Live Map for phpVMS v7  
Vertical flight info card, weather overlays & progress bar

**Author:** Rick Winkelman (Air Berlin Virtual)  
**Tested with:** phpVMS v7 + Leaflet live map

---

## 1. What this file does

This custom `live_map.blade.php` replaces the default phpVMS v7 live map view and adds:

- ✅ Vertical **flight info card** on the **top-right**  
- ✅ Colored **status badge** (BOARDING, ENROUTE, ARRIVED, etc.)  
- ✅ **0–100% progress bar** + circular progress ring  
  - Uses **total route distance** vs **distance remaining**  
  - Shows **remaining time** and **ETA (local)**  
- ✅ **Live weather overlays** from **OpenWeatherMap**:
  - Clouds, radar/precipitation, storms (thunder), wind, temperature  
  - Toggle buttons in a fixed **control box on the bottom-left**  
  - Global opacity slider  
  - Optional “dark map” mode (visual night mode)

This file is designed to be drop-in compatible with phpVMS v7’s built-in ACARS/live map system.

---

## 2. Requirements

- phpVMS **v7** (current dev or stable)  
- A working **live map** (ACARS / smartCARS / etc.)  
- Your site running over **HTTPS**  
- A free **OpenWeatherMap** account + API key (for the weather overlays)

---

## 3. Backup first!

Before you do anything:

1. Locate your current live map view. Common locations are:

   - `resources/views/layouts/live_map.blade.php`  
   - `resources/views/flights/live_map.blade.php`  
   - `modules/Acars/Resources/views/live_map.blade.php`  
   - or inside your **theme** folder, e.g.  
     `resources/views/layouts/SPTheme/live_map.blade.php`

2. **Download and save** a copy of your existing file as a backup, e.g.:

   ```text
   live_map.blade.php.bak


## 4. Installation

Take the new live_map.blade.php from this package.

Upload it to the same path where your original live map file was,
overwriting the old file. For example:

resources/views/layouts/live_map.blade.php


Clear any caching you use:

Laravel cache (if enabled)

Browser cache (Ctrl+F5 / hard refresh)

Go to your phpVMS live map page and confirm:

Aircraft icons are visible

A vertical white info card appears on the top-right when you click a flight

A weather control box appears bottom-left

If you see JS errors in the browser console, scroll down to the Troubleshooting section.


## 5. Configure your OpenWeatherMap API key

The file includes a placeholder for the OpenWeatherMap key.

Create a free account at:
https://home.openweathermap.org

Go to API Keys and copy your key.

Open live_map.blade.php in a text editor and find:

var OWM_API_KEY = "YOUR_OPENWEATHERMAP_API_KEY_HERE"; probably around line 469


Replace it with your key, for example:

var OWM_API_KEY = "abcd1234yourrealkeyhere";


Save the file and hard refresh your browser.

You should now see clouds/radar/etc. when you toggle the buttons in the left weather box.


## 6. Weather control box

Bottom-left you’ll see a fixed box with weather controls:

Clouds – OWM cloud cover tiles

Radar – OWM precipitation / radar

Storms – Thunder / convection

Wind – Winds aloft

Temp – Temperature overlay

Combo – quick button to switch on clouds + radar + storms

Dark map – CSS “night” filter over the base map

Opacity slider – changes opacity for all active weather tiles

All tiles are rendered in a dedicated Leaflet pane with blending to keep labels readable.


## 8. Styling notes

You can tweak the look easily:

Card width/position:
.map-info-card-big

Weather box:
.map-weather-box-left


Status badge colours:
CSS rules starting with .status-badge[data-status*="…"]

If your ACARS uses different status_text values, you can extend the CSS selectors (e.g. add data-status*="INITIAL CLIMB" i).


## 9. Troubleshooting
9.1 No aircraft / map won’t load

Check browser console for errors.

Make sure your phpVMS live map was working before replacing the file.

Confirm you did not change the phpvms.map.render_live_map call at the bottom.

9.2 Weather overlays don’t show

Check the browser console for warnings about OWM_API_KEY.

Confirm the site is served over HTTPS. Mixed-content blocking can hide tiles.

Try opening one of the tile URLs directly in your browser:

https://tile.openweathermap.org/map/clouds_new/3/4/2.png?appid=YOUR_KEY


You should see a PNG cloud tile.


## 10. Credits

Concept & implementation: Rick Winkelman

Built for Air Berlin Virtual and shared with the phpVMS community.

Uses:

Leaflet for map rendering

OpenWeatherMap for tile-based weather overlays

If you modify or redistribute this file, please keep this credit section and mention the original author.

Happy flying and enjoy the new live map! ✈️
