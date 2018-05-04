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
                        <div class="slideTitle">
                            {{slide.title}}
                        </div>
                        <div class="slideText" v-html="slide.text"></div>
                    </div>
                </div>
            </slide>
        </template>
    </carousel>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel';

    export default {
        name: 'slider-achivements',

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
