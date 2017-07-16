<template>
    <div class="FormatSelectContainer">
        <div v-for="(format, ifFormat) in formats">
            <input
                type="checkbox"
                :id="ifFormat"
                :value="ifFormat"
                v-model="checked"
                @change="updateLanguages">
            <label :for="ifFormat">{{ format }}</label>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'FormatSelect',
        props: [
            'displayFormats'
        ],
        methods: {
            updateLanguages: function() {
                this.$emit('updateFormats', this.proxy)
            }
        },
        computed: {
            formats() {
                return window.formats
            },
            checked: {
                get() {
                    return this.displayFormats;
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
