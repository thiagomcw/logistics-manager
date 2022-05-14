<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Add Package Form</div>

                    <div class="card-body">
                        <form @submit.prevent="storePackage" @keypress.enter.prevent>
                            <div class="row mb-3">
                                <div class="col-10">
                                    <label for="description" class="form-label">Description</label>
                                    <input type="text" class="form-control" id="description"
                                           v-model="newPackage.description">
                                    <field-error :errors="formErrors" name="description"/>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <label for="storage-location" class="form-label">Storage Location</label>
                                    <select class="form-select" id="storage-location"
                                            v-model="newPackage.storage_location_id">
                                        <option :value="null"></option>
                                        <option v-for="(location) in storageLocations" :value="location.id">
                                            {{ location.id }}
                                        </option>
                                    </select>
                                    <field-error :errors="formErrors" name="storage_location_id"/>
                                </div>
                                <div class="col-6">
                                    <label for="delivery-date" class="form-label">Delivery Date</label>
                                    <date-picker id="delivery-date" class="w-100" v-model="newPackage.delivery_date"
                                                 format="MM/DD/YYYY" valueType="YYYY-MM-DD"
                                                 :disabled-date="disableDates"/>
                                    <field-error :errors="formErrors" name="delivery_date"/>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="delivery-address" class="form-label">Delivery Address</label>
                                    <vue-google-autocomplete id="delivery-address" classname="form-control" country="us"
                                                             placeholder="Look for an address in US"
                                                             v-on:placechanged="getAddressData">
                                    </vue-google-autocomplete>
                                    <field-error :errors="formErrors" name="delivery_address"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <router-link type="button" class="btn btn-light float-end"
                                                 :to="{name: 'packages.index'}">
                                        Back
                                    </router-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import StorageLocations from "../../api/endpoints/StorageLocations";
import Packages from "../../api/endpoints/Packages";
import FieldError from "../../components/FieldError";
import VueGoogleAutocomplete from "vue-google-autocomplete";

export default {
    name: 'PackagesForm',

    components: {
        FieldError,
        VueGoogleAutocomplete
    },

    data() {
        return {
            addressAutocomplete: null,
            newPackage: {
                storage_location_id: null,
                description: null,
                delivery_address: null,
                delivery_date: null,
            },
            storageLocations: [],
            formErrors: {},
        };
    },

    methods: {
        storePackage() {
            this.formErrors = {};

            Packages
                .store(this.newPackage)
                .then(() => this.$router.push({name: 'packages.index'}))
                .catch((errors) => this.formErrors = errors);
        },

        loadStorageLocations() {
            StorageLocations
                .index({available: 1})
                .then((response) => this.storageLocations = response.data);
        },

        disableDates(date) {
            return date < new Date(new Date().setHours(0, 0, 0, 0));
        },

        getAddressData(addressData, place) {
            this.newPackage.delivery_address = place.formatted_address;
        }
    },

    created() {
        this.loadStorageLocations();
    }
}
</script>
