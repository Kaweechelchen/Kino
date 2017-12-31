<template>
    <div class="LanguageContainer">
        {{ languages[version] }}
        <img src="/../img/3dglasses.svg" v-if="theatreMeta.format == '500'">
        <span class="badge badge-pill badge-secondary">{{theatreMeta.hall}}</span>
    </div>
</template>

<style scoped>
    .LanguageContainer {
        margin-left: 1em;
    }
    img {
        width: 1.8em;
    }
</style>

<script>
    export default {
        name: 'Version',
        props: [
            'version',
            'theatreMeta'
        ],
        computed: {
            languages() {
                return window.languages
            }
        }
    }
</script>
