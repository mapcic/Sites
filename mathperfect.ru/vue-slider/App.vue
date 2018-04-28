<template>
    <!-- :perPageCustom="[[768, 3], [1024, 4]]" -->
        <carousel
            :navigation-enabled="true"
            :perPageCustom="[[100, 1], [640, 2], [800, 3], [1024, 4]]"
            :pagination-enabled="false"
            :loop="true"
            :navigation-next-label="''"
            :navigation-prev-label="''"
        >
            <template v-for="slide in sliders">
                <slide>
                    <!-- <div class="slide"> -->
                    <!-- <div :class="$style.slide">
                        <img :class="$style.img" :src="slide.img"/>
                    </div> -->
                    <!-- <img :class="$style.img" :src="slide.img"/> -->
                    <div :class="$style.slide">
                        <div :class="$style.img" :style="{ 'background-image': 'url(' + slide.img + ')' }"></div>
                    </div>
                    <!-- </div> -->
                </slide>
            </template>
        </carousel>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel'

    export default {
        name: 'app',

        props: {
            sliders: {
                type: Array,
                default: () => []
            },
        },

        data() {
            return {}
        },

        components: {
            Slide,
            Carousel,
        },
    };
</script>

<style>
    .VueCarousel-navigation-next:before {
        content: "\f105";
    }
    .VueCarousel-navigation-prev:before {
        content: "\f104";
    }
</style>

<style lang="scss" module>
    .img {
        width: 100%
    }

    .slide {
        width: 100%;
        height: 100%;

        box-sizing: border-box;
        padding: 0 5px;
        .img {
            background-repeat: no-repeat;
            background-size: cover;
            box-sizing: border-box;
            border: 6px solid black;
            min-width: 100%;
            height: 100%;
        }
    }
</style>
