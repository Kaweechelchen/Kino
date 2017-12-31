<template>
    <div class="MovieContainer">
        <div class="row">
            <div class="col-sm-2 col-xs-3">
                <img :src="poster(movie)">
            </div>
            <div class="col-sm-10 col-xs-9 metaData">
                <div class="row">
                    <theatre
                        class="col-auto"
                        v-for="(versions, theatre) in theatres"
                        :key="theatre"
                        :theatre="theatre"
                        :versions="versions">
                    </theatre>
                    <div class="col">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .MovieContainer {
        background-color: #fff;
        margin: 0.3rem;
    }
    .metaData {
        padding: 0.7em;
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
            'theatres'
        ],
        methods: {
            poster(movieId) {
                return '/../storage/posters/'+movieId+'.jpg'
            }
        }
    }
</script>
