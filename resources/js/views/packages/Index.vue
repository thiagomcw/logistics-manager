<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Packages List</div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-3">
                                <select class="form-select" v-model="selectedStatus">
                                    <option disabled>Filter by status</option>
                                    <option v-for="(status) in statusList" :value="status">
                                        {{ (status || 'all') | upperCaseFirst }}
                                    </option>
                                </select>
                            </div>
                            <div class="col">
                                <router-link class="btn btn-primary float-end" :to="{name: 'packages.form'}">
                                    <i class="bi-plus-lg"></i>
                                    Add Package
                                </router-link>
                            </div>
                        </div>

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th scope="col" class="col-1">Deliver On</th>
                                <th scope="col" class="col-1">Status</th>
                                <th scope="col" class="col-1" title="Storage Location">SL</th>
                                <th scope="col" class="col-3">Description</th>
                                <th scope="col" class="col-5">Address</th>
                                <th scope="col" class="col-1"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in packages">
                                <td>{{ item.delivery_date | dateFormatShow }}</td>
                                <td>{{ item.status | upperCaseFirst }}</td>
                                <td>
                                    <span v-if="item.status === storedStatus">
                                        {{ item.storage_location_id }}
                                    </span>
                                </td>
                                <td>{{ item.description }}</td>
                                <td>{{ item.delivery_address }}</td>
                                <td>
                                    <button v-if="catBeDelivered(item)" class="btn btn-success"
                                            title="Set as delivered" @click="setAsDelivered(item.id)">
                                        <i class="bi-check-lg"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="loading">
                                <td colspan="6" class="text-center">
                                    <loader/>
                                </td>
                            </tr>
                            <tr v-if="!loading && packages.length === 0">
                                <td colspan="6" class="text-center">
                                    No packages found.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Packages from "../../api/endpoints/Packages";
import Loader from "../../components/Loader";

export default {
    name: 'PackagesIndex',

    components: {
        Loader
    },

    data() {
        const storedStatus = 'stored';
        const deliveredStatus = 'delivered';

        return {
            loading: false,
            storedStatus: storedStatus,
            deliveredStatus: deliveredStatus,
            packages: [],
            statusList: [
                null,
                storedStatus,
                deliveredStatus
            ],
            selectedStatus: storedStatus
        };
    },

    watch: {
        selectedStatus(newValue) {
            this.loadPackages(newValue);
        }
    },

    methods: {
        loadPackages(status) {
            this.packages = [];
            this.loading = true;

            Packages
                .index({status})
                .then((response) => {
                    this.packages = response.data;
                    this.loading = false;
                });
        },

        setAsDelivered(id) {
            this.loading = true;
            Packages
                .update(id, {status: this.deliveredStatus})
                .then(() => this.loadPackages(this.storedStatus))
        },

        catBeDelivered(packageItem) {
            return packageItem.status === this.storedStatus && new Date(packageItem.delivery_date) <= new Date();
        }
    },

    created() {
        this.loadPackages('stored');
    }
}
</script>
