<template>
    <div class="ScreeningContainer">
        {{ screening }}
        <movie
            v-for="(versions, movie) in movies"
            :movie="movie"
            :versions="versions"
            :languages="languages">
        </movie>
    </div>
</template>

<script>
    export default {
        name: 'screening',
        props: [
            'screening',
            'movies',
            'languages'
        ]
    }
</script>
