<template>
    <div class="MovieContainer">
        <div class="row">
            <div class="col-sm-2">
                <img :src="poster(movie)">
            </div>
            <div class="col-sm-10">
                <version
                    v-for="(theatres, version) in versions"
                    :version="version"
                    :theatres="theatres">
                </version>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .MovieContainer {
        background-color: #fff;
        margin: 0.3rem;
        padding: 0.75em;
    }
    img {
        max-height: 100px;
    }
</style>

<script>
    export default {
        name: 'Movie',
        props: [
            'movie',
            'versions'
        ],
        computed: {
            movies() {
                return window.movies
            }
        },
        methods: {
            poster(movieId) {
                return '/../storage/posters/'+movieId+'.jpg'
            }
        }
    }
</script>
