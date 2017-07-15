<template>
    <div class="MovieContainer">
        Movie: {{ movie }}
        <version
            v-for="(theatres, version) in versions"
            :version="version"
            :theatres="theatres"
            :languages="languages">
        </version>
    </div>
</template>

<script>
    export default {
        name: 'Movie',
        props: [
            'movie',
            'versions',
            'languages'
        ]
    }
</script>
