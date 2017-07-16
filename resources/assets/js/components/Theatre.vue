<template>
    <div class="TheatreContainer">
        {{ theatres[theatre] }}
        <version
            v-for="(theatreMeta, version) in versions"
            :key="version"
            :version="version"
            :theatreMeta="theatreMeta">
        </version>
    </div>
</template>

<script>
    export default {
        name: 'Theatre',
        props: [
            'theatre',
            'versions'
        ],
        computed: {
            theatres() {
                return window.theatres
            }
        }
    }
</script>
