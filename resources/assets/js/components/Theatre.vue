<template>
    <div class="TheatreContainer">
        {{ theatres[theatre] }}
        <img src="/../img/3dglasses.svg" v-if="theatreMeta.format == '500'">
        <span class="badge badge-default">{{theatreMeta.hall}}</span>
    </div>
</template>

<style scoped>
    img {
        width: 1.8em;
    }
</style>

<script>
    export default {
        name: 'Theatre',
        props: [
            'theatre',
            'theatreMeta'
        ],
        computed: {
            theatres() {
                return window.theatres
            }
        }
    }
</script>
