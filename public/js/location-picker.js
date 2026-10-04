/* public/js/location-picker.js
   Butuh: Leaflet, jQuery, dan window.APP_CONFIG.geoapifyKey (didefinisikan di layout).
   Pakai: const picker = LocationPicker.create({ ...config }); */
window.LocationPicker = (function () {
    const API_KEY = window.APP_CONFIG && window.APP_CONFIG.geoapifyKey;

    const DEFAULTS = {
        center: [-6.2, 106.8167],
        zoom: 10,
        countryCode: 'id',
        lang: 'id',
        placeholder: 'Masukkan lokasi, dan pilih dari daftar yang muncul',
        pinHint: 'Geser pin ke lokasi yang tepat',
        // wajib diisi saat create(): mapId, containerId, inputId, address, lat, lng
    };

    const tileUrl = () => 'https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}'
        + (L.Browser.retina ? '@2x' : '') + '.png?apiKey=' + API_KEY;

    const overlay = typeof LoadingOverlay !== 'undefined' ? LoadingOverlay : null;

    /* ---------- Autocomplete widget ---------- */
    function addressAutocomplete(containerElement, callback, options) {
        const MIN_LENGTH = 3, DEBOUNCE = 300;
        let timeout = null, rejectPrev = null, items = [], focused = -1;

        const inputContainer = document.createElement('div');
        inputContainer.className = 'input-container';
        containerElement.appendChild(inputContainer);

        const input = document.createElement('input');
        input.type = 'text';
        input.placeholder = options.placeholder;
        input.id = options.inputId;
        input.autocomplete = 'off';
        inputContainer.appendChild(input);

        const clearBtn = document.createElement('div');
        clearBtn.className = 'clear-button';
        clearBtn.innerHTML = '<svg viewBox="0 0 24 24" height="24"><path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>';
        clearBtn.addEventListener('click', e => {
            e.stopPropagation();
            input.value = '';
            options.onReset && options.onReset();
            callback(null);
            clearBtn.classList.remove('visible');
            closeList();
        });
        inputContainer.appendChild(clearBtn);

        function closeList() {
            const el = inputContainer.querySelector('.autocomplete-items');
            if (el) inputContainer.removeChild(el);
            focused = -1;
        }

        function setActive(els, index) {
            if (!els || !els.length) return;
            [...els].forEach(el => el.classList.remove('autocomplete-active'));
            els[index].classList.add('autocomplete-active');
            input.value = items[index].formatted;
            callback(items[index]);
        }

        function triggerInput() {
            input.dispatchEvent(new Event('input', { bubbles: true }));
        }

        input.addEventListener('input', function () {
            options.onReset && options.onReset();
            const value = this.value;
            clearBtn.classList.toggle('visible', !!value);

            if (timeout) clearTimeout(timeout);
            if (rejectPrev) rejectPrev({ canceled: true });
            if (!value || value.length < MIN_LENGTH) return;

            timeout = setTimeout(() => {
                timeout = null;
                new Promise((resolve, reject) => {
                    rejectPrev = reject;
                    const url = 'https://api.geoapify.com/v1/geocode/autocomplete'
                        + `?text=${encodeURIComponent(value)}&filter=countrycode:${options.countryCode}`
                        + `&lang=${options.lang}&format=json&limit=10&apiKey=${API_KEY}`;
                    fetch(url).then(r => {
                        rejectPrev = null;
                        r.json().then(d => r.ok ? resolve(d) : reject(d));
                    }).catch(reject);
                }).then(data => {
                    closeList();
                    items = data.results || [];
                    const list = document.createElement('div');
                    list.className = 'autocomplete-items';
                    inputContainer.appendChild(list);
                    items.forEach((result, i) => {
                        const el = document.createElement('div');
                        el.textContent = result.formatted;
                        el.addEventListener('click', () => {
                            input.value = items[i].formatted;
                            callback(items[i]);
                            closeList();
                        });
                        list.appendChild(el);
                    });
                }, err => { if (!err.canceled) console.log(err); });
            }, DEBOUNCE);
        });

        input.addEventListener('keydown', function (e) {
            const list = containerElement.querySelector('.autocomplete-items');
            if (list) {
                const els = list.getElementsByTagName('div');
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    focused = focused !== els.length - 1 ? focused + 1 : 0;
                    setActive(els, focused);
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    focused = focused <= 0 ? els.length - 1 : focused - 1;
                    setActive(els, focused);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if (focused > -1) closeList();
                }
            } else if (e.key === 'ArrowDown') {
                triggerInput();
            }
        });

        document.addEventListener('click', e => {
            if (e.target !== input) closeList();
            else if (!containerElement.querySelector('.autocomplete-items')) triggerInput();
        });
    }

    /* ---------- Location picker ---------- */
    function create(userCfg) {
        if (!API_KEY) throw new Error('LocationPicker: window.APP_CONFIG.geoapifyKey belum diset di layout');

        const cfg = Object.assign({}, DEFAULTS, userCfg);
        ['mapId', 'containerId', 'inputId', 'address', 'lat', 'lng'].forEach(k => {
            if (!cfg[k]) throw new Error(`LocationPicker: config "${k}" wajib diisi`);
        });

        const map = L.map(cfg.mapId).setView(cfg.center, cfg.zoom);
        L.tileLayer(tileUrl(), {
            attribution: 'Powered by <a href="https://www.geoapify.com/" target="_blank">Geoapify</a> | © OpenMapTiles © OpenStreetMap contributors',
            maxZoom: 20,
        }).addTo(map);

        const marker = L.marker(cfg.center, { draggable: true, autoPan: true }).addTo(map);
        marker.bindPopup(cfg.pinHint);

        let located = false;
        const $search = () => $('#' + cfg.inputId);

        function setLokasi(lat, lng) {
            $(cfg.lat).val(lat.toFixed(7));
            $(cfg.lng).val(lng.toFixed(7));
            reverseGeocoding(lat, lng);
        }

        function reverseGeocoding(lat, lon) {
            const url = `https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lon}&apiKey=${API_KEY}&lang=${cfg.lang}`;
            overlay && overlay.show('Mengambil alamat...');
            fetch(url)
                .then(r => r.json())
                .then(result => {
                    const f = result.features && result.features[0];
                    const text = f ? f.properties.formatted : '';
                    $search().val(text);
                    $(cfg.address).val(text);
                    overlay && overlay.hide();
                    if (!f) marker.setPopupContent('Alamat tidak ditemukan. Geser pin sedikit.').openPopup();
                })
                .catch(err => {
                    overlay && overlay.hide();
                    console.error(err);
                    $(cfg.address).val('');
                    marker.setPopupContent('Gagal mengambil alamat. Coba lagi.').openPopup();
                });
        }

        marker.on('dragend', e => {
            const p = e.target.getLatLng();
            setLokasi(p.lat, p.lng);
        });

        function locate(auto = false) {
            if (!('geolocation' in navigator)) {
                if (!auto) alert('Browser tidak mendukung geolokasi.');
                return;
            }
            // const overlay = typeof LoadingOverlay !== 'undefined' ? LoadingOverlay : null;
            overlay && overlay.show('Mencari lokasi Anda...');
            navigator.geolocation.getCurrentPosition(pos => {
                located = true;
                const ll = { lat: pos.coords.latitude, lng: pos.coords.longitude };
                marker.setLatLng(ll);
                map.setView(ll, 17);
                setLokasi(ll.lat, ll.lng);
                if (pos.coords.accuracy > 100) {
                    marker.setPopupContent('Akurasi ±' + Math.round(pos.coords.accuracy) + ' m. ' + cfg.pinHint + '.').openPopup();
                }
                overlay && overlay.hide();
            }, err => {
                overlay && overlay.hide();
                if (err.code === err.PERMISSION_DENIED) {
                    located = true; // auto-locate tidak akan bertanya lagi
                    if (!auto) alert('Izin lokasi ditolak. Aktifkan lewat ikon gembok di address bar, lalu coba lagi.');
                } else {
                    alert('Lokasi tidak bisa diambil. Cari alamat secara manual.');
                }
            }, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
        }

        function locateOnce() {
            if (located) return;
            locate(true);
        }

        // Peta di container tersembunyi perlu dihitung ulang ukurannya saat tampil
        function refresh() { setTimeout(() => map.invalidateSize(), 250); }

        addressAutocomplete(document.getElementById(cfg.containerId), data => {
            if (!data) return;
            map.setView([data.lat, data.lon], 15);
            marker.setLatLng([data.lat, data.lon]);
            $(cfg.address).val(data.formatted);
            $(cfg.lat).val(data.lat.toFixed(7));
            $(cfg.lng).val(data.lon.toFixed(7));
        }, {
            placeholder: cfg.placeholder,
            inputId: cfg.inputId,
            countryCode: cfg.countryCode,
            lang: cfg.lang,
            onReset: () => $(`${cfg.address}, ${cfg.lat}, ${cfg.lng}`).val(''),
        });

        // Restore dari old() setelah validation error
        const oldLat = parseFloat($(cfg.lat).val());
        const oldLng = parseFloat($(cfg.lng).val());
        if (!isNaN(oldLat) && !isNaN(oldLng)) {
            marker.setLatLng([oldLat, oldLng]);
            map.setView([oldLat, oldLng], 16);
            $search().val($(cfg.address).val());
            located = true; // jangan ditimpa geolocation
        }

        return { map, marker, refresh, locate, locateOnce };
    }

    return { create };
})();