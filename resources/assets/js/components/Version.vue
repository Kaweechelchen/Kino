<template>
    <div class="LanguageContainer">
        {{ languages[version] }}
        <theatre
            v-for="(theatreMeta, theatre) in theatres"
            :theatre="theatre"
            :theatreMeta="theatreMeta">
        </theatre>
    </div>
</template>

<script>
    export default {
        name: 'Version',
        props: [
            'version',
            'theatres'
        ],
        computed: {
            languages() {
                return window.languages
            }
        }
    }
</script>
