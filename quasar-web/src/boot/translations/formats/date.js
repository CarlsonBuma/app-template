'use strict';
import { date } from 'quasar';

export default {
    'international': (rawDate) => {
        return !isNaN(Date.parse(rawDate))
            ? date.formatDate(rawDate, 'YYYY-MM-DD') 
            : rawDate
    }, 
    'eu': (rawDate) => {
        return !isNaN(Date.parse(rawDate))
            ? date.formatDate(rawDate, 'DD.MM.YYYY')
            : rawDate
    },
    'us': (rawDate) => {
        return !isNaN(Date.parse(rawDate))
            ? date.formatDate(rawDate, 'MM/DD/YYYY')
            : rawDate
    },
}
