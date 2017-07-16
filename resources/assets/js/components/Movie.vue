<template>
    <div class="MovieContainer">
        <div class="row">
            <div class="col-sm-2 col-xs-3">
                <img :src="poster(movie)">
            </div>
            <div class="col-sm-10 col-xs-9 metaData">
                <theatre
                    v-for="(versions, theatre) in theatres"
                    :theatre="theatre"
                    :versions="versions">
                </theatre>
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
