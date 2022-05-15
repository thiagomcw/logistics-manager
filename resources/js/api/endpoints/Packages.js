import BaseMethods from "../BaseMethods";
import getData from "../utils/getData";

export default new class Packages extends BaseMethods {
    baseUrl = '/api/packages';

    async nextDeliveryDates() {
        return this.api.get(this.baseUrl + '/next-delivery-dates').then(getData);
    }
};
