import Vue from 'vue'
import App from './App.vue'
import SliderCV from './SliderCV.vue'

const sertificates_id = 'sertificates';
const sertificates_dom = document.getElementById(sertificates_id);
let sertificates_sliders = null;
if( sertificates_dom )
    sertificates_sliders = JSON.parse(sertificates_dom.innerHTML);

const VueApp = Vue.extend( App );
const sertificates_app = new VueApp({
    propsData: {
        sliders: sertificates_sliders,
    }
});
sertificates_app.$mount(`#${sertificates_id}`);

const cv_id = 'cv';
const cv_dom = document.getElementById(cv_id);
let cv_sliders = null;
if( cv_dom )
    cv_sliders = JSON.parse(cv_dom.innerHTML);

    console.log( cv_sliders )

const SliderCVApp = Vue.extend( SliderCV );
const cv_app = new SliderCVApp({
    propsData: {
        sliders: cv_sliders,
    }
});
cv_app.$mount( `#${cv_id}` );


// const app = new Vue({
//     el: '#app',
//
//     components: {
//         App,
//     },
//
//     render: h => h( App ),
//
//     mounted() {
//         console.log( this.test )
//     }
// });

// app.$mount('#app');
// const Vue = require( 'vue' );
// console.log( Vue )
