'use strict';
import { date } from 'quasar';

export default { 
    'international': (rawDate) => { 
        return !isNaN(Date.parse(rawDate)) 
            ? date.formatDate(rawDate, 'YYYY-MM-DD HH:mm') 
            : rawDate 
    }, 
    'eu': (rawDate) => { 
        return !isNaN(Date.parse(rawDate)) 
            ? date.formatDate(rawDate, 'DD.MM.YYYY HH:mm') 
            : rawDate 
    }, 
    'us': (rawDate) => { 
        return !isNaN(Date.parse(rawDate)) 
            ? date.formatDate(rawDate, 'MM/DD/YYYY hh:mm A') 
            : rawDate 
    }, 
}
