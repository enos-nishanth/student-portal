@extends('layouts.student')

@section('title', 'Add Delivery Address')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Add Delivery Address
            </h3>

            <p class="text-muted mb-0">
                Enter your location details and select your exact location on the map.
            </p>
        </div>

        <a
            href="{{ route('student.addresses.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form
                action="{{ route('student.addresses.store') }}"
                method="POST"
            >

                @csrf

                <div class="row">

                    {{-- Address --}}
                    <div class="col-12 mb-3">

                        <label
                            for="address"
                            class="form-label"
                        >
                            Address
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            class="form-control"
                            rows="3"
                            placeholder="Enter door number, street, area..."
                        >{{ old('address') }}</textarea>

                    </div>


                    {{-- Town / Area --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="town"
                            class="form-label"
                        >
                            Town / Area
                        </label>

                        <input
                            type="text"
                            name="town"
                            id="town"
                            class="form-control"
                            value="{{ old('town') }}"
                            placeholder="e.g. Kadambathur"
                        >

                    </div>


                    {{-- City --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="city"
                            class="form-label"
                        >
                            City
                        </label>

                        <input
                            type="text"
                            name="city"
                            id="city"
                            class="form-control"
                            value="{{ old('city') }}"
                            placeholder="e.g. Thiruvallur"
                        >

                    </div>


                    {{-- Pincode --}}
                    <div class="col-md-4 mb-3">

                        <label
                            for="pincode"
                            class="form-label"
                        >
                            Pincode
                        </label>

                        <input
                            type="text"
                            name="pincode"
                            id="pincode"
                            class="form-control"
                            value="{{ old('pincode') }}"
                            maxlength="6"
                            placeholder="e.g. 631203"
                        >

                    </div>


                    {{-- Address Type --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="type"
                            class="form-label"
                        >
                            Address Type
                        </label>

                        <select
                            name="type"
                            id="type"
                            class="form-select"
                        >

                            <option value="home">
                                Home
                            </option>

                            <option value="office">
                                Office
                            </option>

                            <option value="other">
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- Default Address --}}
                    <div class="col-md-6 mb-3 d-flex align-items-center">

                        <div class="form-check mt-4">

                            <input
                                type="checkbox"
                                name="is_default"
                                value="1"
                                class="form-check-input"
                                id="is_default"
                            >

                            <label
                                class="form-check-label"
                                for="is_default"
                            >
                                Make this my default address
                            </label>

                        </div>

                    </div>


                    {{-- Map --}}
                    <div class="col-12 mb-3">

                        <label class="form-label">
                            Select Exact Location
                        </label>

                        <div
                            id="map"
                            style="
                                height: 450px;
                                width: 100%;
                                border-radius: 8px;
                            "
                        ></div>

                        <small class="text-muted">
                            Enter your location details above. The map will automatically move to the matching location. You can also drag the marker to select the exact location.
                        </small>

                    </div>


                    {{-- Latitude --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="latitude"
                            class="form-label"
                        >
                            Latitude
                        </label>

                        <input
                            type="text"
                            name="latitude"
                            id="latitude"
                            class="form-control"
                            value="{{ old('latitude') }}"
                            readonly
                        >

                    </div>


                    {{-- Longitude --}}
                    <div class="col-md-6 mb-3">

                        <label
                            for="longitude"
                            class="form-label"
                        >
                            Longitude
                        </label>

                        <input
                            type="text"
                            name="longitude"
                            id="longitude"
                            class="form-control"
                            value="{{ old('longitude') }}"
                            readonly
                        >

                    </div>


                    {{-- Submit --}}
                    <div class="col-12">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-save me-1"></i>
                            Save Address
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<script>

    let map;
    let marker;
    let geocoder;

    let locationTimeout;


    /*
    |--------------------------------------------------------------------------
    | Initialize Google Map
    |--------------------------------------------------------------------------
    */

    function initMap()
    {

        // Default location
        const defaultLocation = {
            lat: 13.0827,
            lng: 80.2707
        };


        /*
        |--------------------------------------------------------------------------
        | Create Map
        |--------------------------------------------------------------------------
        */

        map = new google.maps.Map(
            document.getElementById('map'),
            {
                center: defaultLocation,
                zoom: 11
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Create Marker
        |--------------------------------------------------------------------------
        */

        marker = new google.maps.Marker({

            position: defaultLocation,

            map: map,

            draggable: true

        });


        /*
        |--------------------------------------------------------------------------
        | Geocoder
        |--------------------------------------------------------------------------
        */

        geocoder = new google.maps.Geocoder();


        /*
        |--------------------------------------------------------------------------
        | Initial Coordinates
        |--------------------------------------------------------------------------
        */

        setCoordinates(defaultLocation);


        /*
        |--------------------------------------------------------------------------
        | Watch Form Fields
        |--------------------------------------------------------------------------
        */

        document
            .getElementById('address')
            .addEventListener('input', locationChanged);


        document
            .getElementById('town')
            .addEventListener('input', locationChanged);


        document
            .getElementById('city')
            .addEventListener('input', locationChanged);


        document
            .getElementById('pincode')
            .addEventListener('input', locationChanged);


        /*
        |--------------------------------------------------------------------------
        | Map Click
        |--------------------------------------------------------------------------
        */

        map.addListener(
            'click',
            function(event)
            {

                const location = {

                    lat: event.latLng.lat(),

                    lng: event.latLng.lng()

                };


                marker.setPosition(location);


                setCoordinates(location);


                reverseGeocode(location);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Marker Drag
        |--------------------------------------------------------------------------
        */

        marker.addListener(
            'dragend',
            function(event)
            {

                const location = {

                    lat: event.latLng.lat(),

                    lng: event.latLng.lng()

                };


                setCoordinates(location);


                reverseGeocode(location);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Location Field Changed
    |--------------------------------------------------------------------------
    */

    function locationChanged()
    {

        clearTimeout(locationTimeout);


        locationTimeout = setTimeout(
            function()
            {

                geocodeFormLocation();

            },
            800
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Geocode Form Location
    |--------------------------------------------------------------------------
    */

    function geocodeFormLocation()
    {

        const address =
            document.getElementById('address').value.trim();


        const town =
            document.getElementById('town').value.trim();


        const city =
            document.getElementById('city').value.trim();


        const pincode =
            document.getElementById('pincode').value.trim();


        /*
        |--------------------------------------------------------------------------
        | Build Search Query
        |--------------------------------------------------------------------------
        */

        const parts = [];


        if (address) {

            parts.push(address);

        }


        if (town) {

            parts.push(town);

        }


        if (city) {

            parts.push(city);

        }


        if (pincode) {

            parts.push(pincode);

        }


        /*
        |--------------------------------------------------------------------------
        | Don't Search Too Early
        |--------------------------------------------------------------------------
        */

        if (parts.length === 0) {

            return;

        }


        const query =
            parts.join(', ');


        /*
        |--------------------------------------------------------------------------
        | Google Geocoding
        |--------------------------------------------------------------------------
        */

        geocoder.geocode(
            {
                address: query
            },
            function(results, status)
            {

                if (
                    status !== 'OK' ||
                    !results[0]
                ) {

                    console.log(
                        'Location not found:',
                        status
                    );

                    return;

                }


                const result = results[0];


                const location = {

                    lat: result.geometry.location.lat(),

                    lng: result.geometry.location.lng()

                };


                /*
                |--------------------------------------------------------------------------
                | Move Map
                |--------------------------------------------------------------------------
                */

                map.setCenter(location);


                map.setZoom(15);


                /*
                |--------------------------------------------------------------------------
                | Move Marker
                |--------------------------------------------------------------------------
                */

                marker.setPosition(location);


                /*
                |--------------------------------------------------------------------------
                | Set Coordinates
                |--------------------------------------------------------------------------
                */

                setCoordinates(location);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Reverse Geocoding
    |--------------------------------------------------------------------------
    */

    function reverseGeocode(location)
    {

        geocoder.geocode(
            {
                location: location
            },
            function(results, status)
            {

                if (
                    status !== 'OK' ||
                    !results[0]
                ) {

                    console.log(
                        'Reverse geocoding failed:',
                        status
                    );

                    return;

                }


                const result = results[0];


                /*
                |--------------------------------------------------------------------------
                | Fill Address
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('address')
                    .value =
                    result.formatted_address;


                /*
                |--------------------------------------------------------------------------
                | Extract Components
                |--------------------------------------------------------------------------
                */

                let town = '';

                let city = '';

                let pincode = '';


                result.address_components.forEach(
                    function(component)
                    {

                        const types =
                            component.types;


                        /*
                        | Town / Area
                        */

                        if (
                            types.includes(
                                'sublocality'
                            ) ||
                            types.includes(
                                'sublocality_level_1'
                            ) ||
                            types.includes(
                                'locality'
                            )
                        ) {

                            if (!town) {

                                town =
                                    component.long_name;

                            }

                        }


                        /*
                        | City
                        */

                        if (
                            types.includes(
                                'administrative_area_level_2'
                            )
                        ) {

                            city =
                                component.long_name;

                        }


                        /*
                        | Pincode
                        */

                        if (
                            types.includes(
                                'postal_code'
                            )
                        ) {

                            pincode =
                                component.long_name;

                        }

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Fill Fields
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('town')
                    .value = town;


                document
                    .getElementById('city')
                    .value = city;


                document
                    .getElementById('pincode')
                    .value = pincode;


                /*
                |--------------------------------------------------------------------------
                | Coordinates
                |--------------------------------------------------------------------------
                */

                setCoordinates(location);

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Set Coordinates
    |--------------------------------------------------------------------------
    */

    function setCoordinates(location)
    {

        document
            .getElementById('latitude')
            .value =
            location.lat.toFixed(8);


        document
            .getElementById('longitude')
            .value =
            location.lng.toFixed(8);

    }

</script>


<script
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&callback=initMap"
    async
    defer>
</script>

@endpush