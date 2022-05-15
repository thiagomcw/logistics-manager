<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Delivery Map</div>
                    <div class="card-body">
                        <div class="col-4 mb-3">
                            <select class="form-select" v-model="deliveryDate" @change="loadPackages">
                                <option :value="currentDate">Today</option>
                                <option v-for="(nextDate) in nextDeliveryDates" :value="nextDate">
                                    {{ nextDate | dateFormatShow }}
                                </option>
                            </select>
                        </div>
                        <div class="col-12 mb-1" v-if="packages && packages.length === 0">No packages to deliver.</div>
                        <div class="col-12 text-center" v-if="loading">
                            <loader/>
                        </div>
                        <div id="map"></div>
                        <p class="help-block mt-1">
                            Note: The last point is also the first one. For example, if the last point is F that means
                            it also represents the point A. It happens because the truck has to come back to our garage.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Packages from "../../api/endpoints/Packages";
import moment from "moment";
import mapDirections from "../../config/mapDirections";
import Loader from "../../components/Loader";

export default {
    name: 'DeliveryMapIndex',

    components: {
        Loader
    },

    data() {
        const currentDate = moment().format('YYYY-MM-DD');

        return {
            loading: false,
            packages: null,
            nextDeliveryDates: [],
            deliveryDate: currentDate,
            currentDate: currentDate,
            storedStatus: 'stored',
            mapDirectionsService: null,
            mapDirectionsRender: null,
        };
    },


    methods: {
        loadNextDeliveryDates() {
            Packages
                .nextDeliveryDates()
                .then((response) => this.nextDeliveryDates = response.data);
        },

        loadPackages() {
            this.loading = true;

            Packages
                .index({
                    status: this.storedStatus,
                    delivery_date: this.deliveryDate
                })
                .then((response) => {
                    this.loading = false;
                    this.packages = response.data;
                    this.defineMapDirectionsWayPoints();
                });
        },

        defineMapDirectionsWayPoints() {
            let waypoints = [];

            this.packages.forEach((item) => waypoints.push({location: item.delivery_address}));

            this.defineMapDirectionsRoute(waypoints)
        },

        initMapDirections() {
            this.mapDirectionsService = new google.maps.DirectionsService();
            this.mapDirectionsRender = new google.maps.DirectionsRenderer();
            const map = new google.maps.Map(document.getElementById('map'));

            this.mapDirectionsRender.setMap(map);
        },

        defineMapDirectionsRoute(waypoints) {
            this.loading = true;
            this.mapDirectionsService
                .route({
                    origin: mapDirections.defaultAddress,
                    destination: mapDirections.defaultAddress,
                    waypoints: waypoints,
                    optimizeWaypoints: true,
                    travelMode: google.maps.TravelMode.DRIVING,
                })
                .then((response) => {
                    this.mapDirectionsRender.setDirections(response);
                    this.loading = false;
                })
                .catch(() => {
                    this.mapErrorMessage = 'It was not possible to load the route. Please, try again later!'
                    this.loading = false;
                });
        }
    },

    mounted() {
        this.initMapDirections();
        this.loadNextDeliveryDates();
        this.loadPackages(this.currentDate);
    }
}
</script>

<style lang="scss" scoped>
#map {
    height: 400px;
}

.help-block {
    font-size: 11px;
}
</style>
