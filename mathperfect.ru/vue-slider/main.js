import Vue from 'vue'
import App from './App.vue'
import SliderCV from './SliderCV.vue'
import SliderAchivements from './SliderAchivements.vue'

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

const SliderCVApp = Vue.extend( SliderCV );
const cv_app = new SliderCVApp({
    propsData: {
        sliders: cv_sliders,
    }
});
cv_app.$mount( `#${cv_id}` );

const as_id = 'achivements_student';
const as_dom = document.getElementById(as_id);
const as_sliders = window.as_sliders? window.as_sliders : [];

const SliderAchivementsApp = Vue.extend( SliderAchivements );
const as_app = new SliderAchivementsApp({
    propsData: {
        sliders: as_sliders,
    }
});

if( as_dom )
    as_app.$mount( `#${as_id}` );

// const ap_id = 'achivements_pupils';
// const ap_dom = document.getElementById(ap_id);
// let ap_sliders = null;
// if( ap_dom )
//     ap_sliders = JSON.parse(ap_dom.innerHTML);
//
// const ap_app = new SliderAchivementsApp({
//     propsData: {
//         sliders: ap_sliders,
//     }
// });
// ap_app.$mount( `#${ap_id}` );
