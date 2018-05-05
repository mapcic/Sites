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
                <div class="slideAchievement">
                    <div class="slideWrap">
                        <h3 class="slideTitle">
                            {{ slide.title }}
                        </h3>
                        <img :src="slide.img" class="slideImg">
                        <div class="slideText">
                            {{ slide.text }}
                        </div>
                    </div>
                </div>
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
