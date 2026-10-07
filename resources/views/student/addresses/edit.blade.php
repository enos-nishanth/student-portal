@extends('layouts.student')

@section('title', 'Edit Delivery Address')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-1">
                Edit Delivery Address
            </h3>

            <p class="text-muted mb-0">
                Update your delivery location and address.
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
                action="{{ route('student.addresses.update', $deliveryAddress) }}"
                method="POST"
            >

                @csrf
                @method('PUT')

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
                        >{{ old('address', $deliveryAddress->address) }}</textarea>

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
                            value="{{ old('town', $deliveryAddress->town) }}"
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
                            value="{{ old('city', $deliveryAddress->city) }}"
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
                            value="{{ old('pincode', $deliveryAddress->pincode) }}"
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

                            <option
                                value="home"
                                {{ old('type', $deliveryAddress->type) === 'home' ? 'selected' : '' }}
                            >
                                Home
                            </option>

                            <option
                                value="office"
                                {{ old('type', $deliveryAddress->type) === 'office' ? 'selected' : '' }}
                            >
                                Office
                            </option>

                            <option
                                value="other"
                                {{ old('type', $deliveryAddress->type) === 'other' ? 'selected' : '' }}
                            >
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
                                {{ old('is_default', $deliveryAddress->is_default) ? 'checked' : '' }}
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
                            You can click on the map or drag the marker to update the exact location.
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
                            value="{{ old('latitude', $deliveryAddress->latitude) }}"
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
                            value="{{ old('longitude', $deliveryAddress->longitude) }}"
                            readonly
                        >

                    </div>


                    {{-- Submit --}}
                    <div class="col-12">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-pencil-square me-1"></i>
                            Update Address
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
    | Initialize Map
    |--------------------------------------------------------------------------
    */

    function initMap()
    {

        const existingLocation = {

            lat: {{ $deliveryAddress->latitude ?? 13.0827 }},

            lng: {{ $deliveryAddress->longitude ?? 80.2707 }}

        };


        /*
        |--------------------------------------------------------------------------
        | Create Map
        |--------------------------------------------------------------------------
        */

        map = new google.maps.Map(

            document.getElementById('map'),

            {
                center: existingLocation,
                zoom: 15
            }

        );


        /*
        |--------------------------------------------------------------------------
        | Create Marker
        |--------------------------------------------------------------------------
        */

        marker = new google.maps.Marker({

            position: existingLocation,

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
        | Set Existing Coordinates
        |--------------------------------------------------------------------------
        */

        setCoordinates(existingLocation);


        /*
        |--------------------------------------------------------------------------
        | Watch Address Fields
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
        | Click Map
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
        | Drag Marker
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
    | Address Fields Changed
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
                | Update Coordinates
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
                | Address
                |--------------------------------------------------------------------------
                */

                document
                    .getElementById('address')
                    .value =
                    result.formatted_address;


                /*
                |--------------------------------------------------------------------------
                | Extract Location Components
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
                            types.includes('sublocality') ||
                            types.includes('sublocality_level_1') ||
                            types.includes('locality')
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