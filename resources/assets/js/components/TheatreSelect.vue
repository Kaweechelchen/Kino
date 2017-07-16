<template>
    <div class="TheatreSelectContainer">
        <div v-for="(name, idTheatre) in theatres">
            <input
                type="checkbox"
                :id="idTheatre"
                :value="idTheatre"
                v-model="checked"
                @change="updateTheatres">
            <label
                :for="idTheatre">
                {{ name }}
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'TheatreSelect',
        props: [
            'displayTheatres'
        ],
        methods: {
            updateTheatres: function() {
                this.$emit('updateTheatres', this.proxy)
            }
        },
        computed: {
            theatres () {
                return window.theatres
            },
            checked: {
                get() {
                    return this.displayTheatres;
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
