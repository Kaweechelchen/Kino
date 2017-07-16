<template>
    <div class="LanguageSelectContainer">
        <div v-for="(languages, idLanguage) in languages">
            <input
                type="checkbox"
                :value="idLanguage"
                v-model="checked"
                @change="updateLanguages">
            <label :for="idLanguage">{{ languages }}</label>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'LanguageSelect',
        props: [
            'displayLanguages'
        ],
        methods: {
            updateLanguages: function() {
                this.$emit('updateLanguages', this.proxy)
            }
        },
        computed: {
            languages() {
                return window.languages
            },
            checked: {
                get() {
                    return this.displayLanguages;
                },
                set(value) {
                    this.proxy = value;
                }
            }
        },
        data() {
            return {
                proxy: false
            }
        }
    }
</script>
