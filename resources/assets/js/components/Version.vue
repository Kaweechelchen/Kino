<template>
    <div class="LanguageContainer">
        {{ version }} :: {{ languages[version] }}
    </div>
</template>

<script>
    export default {
        name: 'Version',
        props: [
            'version',
            'theatres',
            'languages'
        ]
    }
</script>
