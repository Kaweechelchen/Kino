<template>
    <div class="LanguageSelectContainer">
        <div v-for="(languages, idLanguage) in languages">
            <input
                type="checkbox"
                :value="idLanguage"
                v-model="displayLanguages"
                @change="updateLanguages">
            <label :for="idLanguage">{{ languages }}</label>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'LanguageSelect',
        props: [
            'languages',
            'displayLanguages'
        ],
        methods: {
            updateLanguages: function() {
                this.$emit('updateLanguages', this.displayLanguages)
            }
        }
    }
</script>
