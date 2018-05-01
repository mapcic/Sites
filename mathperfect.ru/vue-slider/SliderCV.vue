<template>
    <carousel
        :navigation-enabled="true"
        :per-page="1"
        :pagination-enabled="false"
        :loop="true"
        :navigation-next-label="''"
        :navigation-prev-label="''"
    >
        <template v-for="slide in sliders">
            <slide>
                <div class="slideCV">
                    <div class="slideWrap">
                        <div class="slideTextWrap">
                            <h3 class="slideTitle">
                                {{ slide.title }}
                            </h3>
                            <div class="slideText">
                                {{ slide.text }}
                            </div>
                            <div class="slideButtons">
                                <a class="slideButton" :href="slide.href">Читать подробнее...</a>
                            </div>
                        </div>
                        <div class="slideImg" :style="{ 'background-image': 'url(' + slide.img + ')' }"></div>
                    </div>
                </div>
                <!-- <div class="slide"> -->
                <!-- <div :class="$style.slide">
                    <img :class="$style.img" :src="slide.img"/>
                </div> -->
                <!-- <img :class="$style.img" :src="slide.img"/> -->
                <!-- <div :class="$style.slide">
                    <div :class="$style.img" :style="{ 'background-image': 'url(' + slide.img + ')' }"></div>
                </div> -->
                <!-- </div> -->
            </slide>
        </template>
    </carousel>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel';

    export default {
        name: 'slider-cv',

        props: {
            sliders: {
                type: Array,
                default: () => ([]),
            },
        },

        data() {
            return {}
        },

        components: {
            Carousel,
            Slide,
        }
    }
</script>

<style></style>
