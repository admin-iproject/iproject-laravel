/**
 * geocoder.js — Silent address → lat/lng via Nominatim (OpenStreetMap)
 * No API key required. Exposed as window.geocodeAddress().
 *
 * Usage:
 *   const coords = await window.geocodeAddress('91 Labree Road', 'New Boston', 'NH', '03070');
 *   if (coords) { coords.lat, coords.lng }
 */
window.geocodeAddress = async function(addressLine1, city, state, zip = '', country = 'US') {
    const parts = [addressLine1, city, state, zip, country].filter(Boolean);
    if (!parts.length) return null;

    const q = parts.join(', ');
    const url = 'https://nominatim.openstreetmap.org/search?'
        + new URLSearchParams({ q, format: 'json', limit: 1, addressdetails: 0 });

    try {
        const res  = await fetch(url, {
            headers: { 'User-Agent': 'YourApp/1.0' },
        });
        const data = await res.json();
        if (data && data[0]) {
            return { lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) };
        }
    } catch (e) {
        console.warn('[Geocoder] Failed for:', q, e.message);
    }
    return null;
};

/**
 * Helper: given a form element, read address fields and silently
 * update hidden lat/lng inputs.
 *
 * @param {object} opts
 *   addressSelector  - CSS selector for address_line1 input
 *   citySelector     - CSS selector for city input
 *   stateSelector    - CSS selector for state input
 *   zipSelector      - CSS selector for zip input (optional)
 *   latSelector      - CSS selector for hidden lat input
 *   lngSelector      - CSS selector for hidden lng input
 *   statusSelector   - CSS selector for optional status indicator element
 *   context          - parent element to scope selectors (default: document)
 */
window.geocodeFormAddress = async function(opts = {}) {
    const ctx  = opts.context || document;
    const get  = sel => sel ? ctx.querySelector(sel)?.value?.trim() : '';
    const setEl = (sel, val) => { const el = sel ? ctx.querySelector(sel) : null; if (el) el.value = val; };
    const statusEl = opts.statusSelector ? ctx.querySelector(opts.statusSelector) : null;

    const addr  = get(opts.addressSelector);
    const city  = get(opts.citySelector);
    const state = get(opts.stateSelector);
    const zip   = get(opts.zipSelector);

    if (!addr && !city) return; // nothing to geocode

    if (statusEl) {
        statusEl.textContent = '📍 Locating…';
        statusEl.className   = statusEl.className.replace(/text-\w+-\d+/g, '') + ' text-blue-500';
    }

    const coords = await window.geocodeAddress(addr, city, state, zip);

    if (coords) {
        setEl(opts.latSelector, coords.lat);
        setEl(opts.lngSelector, coords.lng);
        if (statusEl) {
            statusEl.textContent = `📍 ${coords.lat.toFixed(4)}, ${coords.lng.toFixed(4)}`;
            statusEl.className   = statusEl.className.replace(/text-\w+-\d+/g, '') + ' text-green-600';
        }
        return coords;
    } else {
        if (statusEl) {
            statusEl.textContent = '📍 Address not found';
            statusEl.className   = statusEl.className.replace(/text-\w+-\d+/g, '') + ' text-amber-500';
        }
        return null;
    }
};
