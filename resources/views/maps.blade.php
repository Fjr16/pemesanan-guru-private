@extends('layouts.app')

@section('title', 'Maps Test')
@section('content')

    <head>
        {{-- css --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
        <style>
            #map { height: 100vh; }
            .autocomplete-container {
                margin-bottom: 20px;
            }

            .input-container {
                display: flex;
                position: relative;
            }

            .autocomplete-items {
                position: absolute;
                border: 1px solid rgba(0, 0, 0, 0.1);
                box-shadow: 0px 2px 10px 2px rgba(0, 0, 0, 0.1);
                border-top: none;
                background-color: #fff;

                z-index: 999;
                top: calc(100% + 2px);
                left: 0;
                right: 0;
            }

            .autocomplete-items div {
                padding: 10px;
                cursor: pointer;
            }

            .autocomplete-items div:hover {
                /*when hovering an item:*/
                background-color: rgba(0, 0, 0, 0.1);
            }

            .autocomplete-items .autocomplete-active {
                /*when navigating through the items using the arrow keys:*/
                background-color: rgba(0, 0, 0, 0.1);
            }

            .input-container input {
                flex: 1;
                outline: none;
                
                border: 1px solid rgba(0, 0, 0, 0.2);
                padding: 10px;
                padding-right: 31px;
                font-size: 16px;
            }

            .clear-button {
                color: rgba(0, 0, 0, 0.4);
                cursor: pointer;
                
                position: absolute;
                right: 5px;
                top: 0;

                height: 100%;
                display: none;
                align-items: center;
                }

                .clear-button.visible {
                display: flex;
                }

                .clear-button:hover {
                color: rgba(0, 0, 0, 0.6);
                }
        </style>

        {{-- js --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    </head>

    <div style="background:#f8f9fc;min-height:calc(100vh - 200px);padding:32px 0;">
        <div style="max-width:720px;margin:0 auto;padding:0 20px;">
            <div class="autocomplete-container" id="autocomplete-container"></div>
            <div id="map" style="height: 400px;"></div>

            <div class="card mt-4 p-3">
                <div class="form-group">
                    <label for="lat">Alamat:</label>
                    <input type="text" class="form-control" id="address" placeholder="Geser pin ke lokasi yang tepat" readonly>
                </div>
                <div class="form-group">
                    <label for="lat">Latitude:</label>
                    <input type="text" class="form-control" id="lat" placeholder="Latitude" readonly>
                </div>
                <div class="form-group">
                    <label for="lng">Longitude:</label>
                    <input type="text" class="form-control" id="lng" placeholder="Longitude" readonly>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script type="text/javascript">
        const map = L.map('map').setView([-6.2000, 106.8167], 10);
        const myAPIKey = @json(config('services.geoapify.frontend_key'));
        const isRetina = L.Browser.retina;
        const baseUrl = "https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}.png?apiKey=" + myAPIKey;
        const retinaUrl = "https://maps.geoapify.com/v1/tile/osm-bright/{z}/{x}/{y}@2x.png?apiKey=" + myAPIKey;
        L.tileLayer(isRetina ? retinaUrl : baseUrl, {
            attribution: 'Powered by <a href="https://www.geoapify.com/" target="_blank">Geoapify</a> | <a href="https://openmaptiles.org/" target="_blank">© OpenMapTiles</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap</a> contributors',
            maxZoom: 20, 
        }).addTo(map);

        var marker = L.marker([-6.2, 106.8167], {
            draggable: true,
            autoPan: true,
        }).addTo(map);

        marker.bindPopup('Geser pin ke lokasi yang tepat');
        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            setLokasi(pos.lat, pos.lng);
        });

        function setLokasi(lat, lng) {
            $('#lat').val(lat.toFixed(7));
            $('#lng').val(lng.toFixed(7));
            reverseGeocoding(lat, lng);
        }

        //geolocation
        $(document).ready(function() {
            if(!("geolocation" in navigator)) return;
            LoadingOverlay.show('Mencari lokasi Anda...');
            const options = {
                enableHighAccuracy: true, // Requests the best possible results (e.g., GPS)
                timeout: 10000,           // Maximum time allowed to find location (10 seconds)
                maximumAge: 0             // Forces the browser to look up a fresh position
            };

            navigator.geolocation.getCurrentPosition(showPosition, showError, options);
        });

        // 2. Success callback function
        function showPosition(position) {
            $('#alamat-spinner').addClass('d-none');
            const pos = { lat: position.coords.latitude, lng: position.coords.longitude};
            marker.setLatLng(pos);
            map.setView(pos, 17);
            setLokasi(pos.lat, pos.lng);
            if (position.coords.accuracy > 100) {
                marker.setPopupContent('Akurasi ±' + Math.round(position.coords.accuracy) + ' m. Geser pin ke lokasi yang tepat.').openPopup();
            }
            LoadingOverlay.hide();
        }

        // 3. Error callback function
        function showError(error) {
            LoadingOverlay.hide();
            if (error.code === error.PERMISSION_DENIED) {
                console.warn('Izin lokasi ditolak, memakai lokasi default.');
            } else {
                alert('Lokasi tidak bisa diambil. Cari alamat secara manual.');
            }
        }

        // reverse geocoding
        function reverseGeocoding(lat,lon){
            const geocodeUrl = `https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lon}&apiKey=${myAPIKey}&lang=id`;
            var requestOptions = {
                method: 'GET',
            };

            fetch(geocodeUrl, requestOptions)
            .then(response => response.json())
            .then(result => {
                if (result.features && result.features.length > 0) {
                    const feature = result.features[0];
                    $('#searchBar').val(feature.properties.formatted);
                    $('#address').val(feature.properties.formatted);
                    $('#lat').val(lat.toFixed(7));
                    $('#lng').val(lon.toFixed(7));
                    // marker.bindPopup(feature.properties.formatted).openPopup();
                } else {
                    $('#searchBar').val('');
                    $('#address').val('Alamat tidak ditemukan.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#searchBar').val('');
                $('#address').val('Terjadi kesalahan saat mencari alamat.');
            });
        }

        function addressAutocomplete(containerElement, callback, options) {
            let currentTimeout = null;
            let currentPromiseReject = null;

            // create container for input element
            const inputContainerElement = document.createElement("div");
            inputContainerElement.setAttribute("class", "input-container");
            containerElement.appendChild(inputContainerElement);

            // create input element
            const inputElement = document.createElement("input");
            inputElement.setAttribute("type", "text");
            inputElement.setAttribute("placeholder", options.placeholder);
            inputElement.setAttribute("id", "searchBar");
            inputContainerElement.appendChild(inputElement);

            // step 2: 
            const MIN_ADDRESS_LENGTH = 3;
            const DEBOUNCE_DELAY = 300;

            var currentItems;

            // add input field clear button
            const clearButton = document.createElement("div");
            clearButton.classList.add("clear-button");
            addIcon(clearButton);
            clearButton.addEventListener("click", (e) => {
                e.stopPropagation();
                inputElement.value = '';
                callback(null);
                clearButton.classList.remove("visible");
                closeDropDownList();
            });
            inputContainerElement.appendChild(clearButton);

            function addIcon(buttonElement) {
                const svgElement = document.createElementNS("http://www.w3.org/2000/svg", 'svg');
                svgElement.setAttribute('viewBox', "0 0 24 24");
                svgElement.setAttribute('height', "24");

                const iconElement = document.createElementNS("http://www.w3.org/2000/svg", 'path');
                iconElement.setAttribute("d", "M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z");
                iconElement.setAttribute('fill', 'currentColor');
                svgElement.appendChild(iconElement);
                buttonElement.appendChild(svgElement);
            }

            /* Process a user input: */
            inputElement.addEventListener("input", function(e) {
                const currentValue = this.value;

                if (!currentValue) {
                    clearButton.classList.remove("visible");
                }

                // Show clearButton when there is a text
                // clearButton.classList.add("visible");
                clearButton.classList.toggle("visible", !!currentValue);

                // Cancel previous timeout
                if (currentTimeout) {
                    clearTimeout(currentTimeout);
                }

                // Cancel previous request promise
                if (currentPromiseReject) {
                    currentPromiseReject({
                        canceled: true
                    });
                }

                // Skip empty or short address strings
                if (!currentValue || currentValue.length < MIN_ADDRESS_LENGTH) {
                    return false;
                }

                /* Call the Address Autocomplete API with a delay */
                currentTimeout = setTimeout(() => {
                    currentTimeout = null;
                        
                    /* Create a new promise and send geocoding request */
                    const promise = new Promise((resolve, reject) => {
                        currentPromiseReject = reject;
                        var url = `https://api.geoapify.com/v1/geocode/autocomplete?text=${encodeURIComponent(currentValue)}&filter=countrycode:id&lang=id&format=json&limit=10&apiKey=${myAPIKey}`;

                        fetch(url)
                        .then(response => {
                            currentPromiseReject = null;

                            // check if the call was successful
                            if (response.ok) {
                            response.json().then(data => resolve(data));
                            } else {
                            response.json().then(data => reject(data));
                            }
                        });
                    });

                    promise.then((data) => {
                        // here we get address suggestions
                        currentItems = data.results;
                        /*create a DIV element that will contain the items (values):*/
                        const autocompleteItemsElement = document.createElement("div");
                        autocompleteItemsElement.setAttribute("class", "autocomplete-items");
                        inputContainerElement.appendChild(autocompleteItemsElement);

                        /* For each item in the results */
                        data.results.forEach((result, index) => {
                            /* Create a DIV element for each element: */
                            const itemElement = document.createElement("div");
                            /* Set formatted address as item value */
                            // itemElement.innerHTML = result.formatted;
                            itemElement.textContent = result.formatted;
                            autocompleteItemsElement.appendChild(itemElement);

                            /* Set the value for the autocomplete text field and notify: */
                            itemElement.addEventListener("click", function(e) {
                                inputElement.value = currentItems[index].formatted;
                                callback(currentItems[index]);
                                /* Close the list of autocompleted values: */
                                closeDropDownList();
                            });
                        });
                    }, (err) => {
                        if (!err.canceled) {
                            console.log(err);
                        }
                    });
                }, DEBOUNCE_DELAY);
            });

            /* Focused item in the autocomplete list. This variable is used to navigate with buttons */
            let focusedItemIndex = -1;
            /* Add support for keyboard navigation */
            inputElement.addEventListener("keydown", function(e) {
                var autocompleteItemsElement = containerElement.querySelector(".autocomplete-items");
                if (autocompleteItemsElement) {
                    var itemElements = autocompleteItemsElement.getElementsByTagName("div");
                    if (e.keyCode == 40) {
                        e.preventDefault();
                        /*If the arrow DOWN key is pressed, increase the focusedItemIndex variable:*/
                        focusedItemIndex = focusedItemIndex !== itemElements.length - 1 ? focusedItemIndex + 1 : 0;
                        /*and and make the current item more visible:*/
                        setActive(itemElements, focusedItemIndex);
                    } else if (e.keyCode == 38) {
                        e.preventDefault();

                        /*If the arrow UP key is pressed, decrease the focusedItemIndex variable:*/
                        // focusedItemIndex = focusedItemIndex !== 0 ? focusedItemIndex - 1 : focusedItemIndex = (itemElements.length - 1);
                        focusedItemIndex = focusedItemIndex <= 0 ? focusedItemIndex = (itemElements.length - 1) : focusedItemIndex - 1;
                        /*and and make the current item more visible:*/
                        setActive(itemElements, focusedItemIndex);
                    } else if (e.keyCode == 13) {
                        /* If the ENTER key is pressed and value as selected, close the list*/
                        e.preventDefault();
                        if (focusedItemIndex > -1) {
                            closeDropDownList();
                        }
                    }
                } else {
                if (e.keyCode == 40) {
                    /* Open dropdown list again */
                    var event = document.createEvent('Event');
                    event.initEvent('input', true, true);
                    inputElement.dispatchEvent(event);
                }
                }
            });

            function setActive(items, index) {
                if (!items || !items.length) return false;

                for (var i = 0; i < items.length; i++) {
                    items[i].classList.remove("autocomplete-active");
                }

                /* Add class "autocomplete-active" to the active element*/
                items[index].classList.add("autocomplete-active");

                // Change input value and notify
                inputElement.value = currentItems[index].formatted;
                callback(currentItems[index]);
            }

            function closeDropDownList() {
                var autocompleteItemsElement = inputContainerElement.querySelector(".autocomplete-items");
                if (autocompleteItemsElement) {
                    inputContainerElement.removeChild(autocompleteItemsElement);
                }
                focusedItemIndex = -1;
            }

            /* Close the autocomplete dropdown when the document is clicked. 
                Skip, when a user clicks on the input field */
            document.addEventListener("click", function(e) {
                if (e.target !== inputElement) {
                closeDropDownList();
                } else if (!containerElement.querySelector(".autocomplete-items")) {
                // open dropdown list again
                var event = document.createEvent('Event');
                event.initEvent('input', true, true);
                inputElement.dispatchEvent(event);
                }
            });
        }

        addressAutocomplete(document.getElementById("autocomplete-container"), (data) => {
            if (data) {
                const lat = data.lat;
                const lon = data.lon;
                map.setView([lat, lon], 15);
                marker.setLatLng([lat, lon]);
                $('#address').val(data.formatted);
                $('#lat').val(lat.toFixed(7));
                $('#lng').val(lon.toFixed(7));
            }
        }, {
            placeholder: "Masukkan lokasi"
        });
    </script>
    @endpush
@endsection