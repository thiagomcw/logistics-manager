import Vue from 'vue';
import moment from "moment";

Vue.filter("dateFormatShow", str => moment(str).format('MM/DD/YYYY'))

Vue.filter("upperCaseFirst", str => str.charAt(0).toUpperCase() + str.slice(1));

