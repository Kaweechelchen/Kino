<template>
    <div class="MoviesContainer">
        <div class="col-md-3">
            <theatre-select
                :theatres="theatres"
                :displayTheatres="displayTheatres"
                v-on:updateTheatres="updateTheatres"></theatre-select>
            <language-select
                :languages="languages"
                :displayLanguages="displayLanguages"
                v-on:updateLanguages="updateLanguages"></language-select>
        </div>
        <div class="col-md-9">
            <screening
                v-for="(movies, screening) in screenings"
                :screening="screening"
                :movies="movies"
                :languages="languages">
            </screening>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                theatres: theatres,
                displayTheatres: [
                    'UCUTO',
                    'CSTAR',
                    'UBVAL',
                    'UKRCH'
                ],
                languages: languages,
                displayLanguages: [
                    '302',
                    '305',
                    '313',
                    '727',
                    '728',
                    '730'
                ],
                displayFormats: [
                    '497'
                ],
                screeningsRaw: screenings
            }
        },
        methods: {
            updateTheatres: function(displayTheatres) {
                this.displayTheatres = displayTheatres
            },
            updateLanguages: function(displayLanguages) {
                this.displayLanguages = displayLanguages
            }
        },
        computed: {
            screenings: function() {
                var screenings = {};
                for (var screening in this.screeningsRaw) {
                    for (var movie in this.screeningsRaw[screening]) {
                        for (var language in this.screeningsRaw[screening][movie]) {
                            if (this.displayLanguages.indexOf(language) == -1) {
                                continue;
                            }
                            for (var theatre in this.screeningsRaw[screening][movie][language]) {
                                if (this.displayTheatres.indexOf(theatre) == -1) {
                                    continue;
                                }

                                if (this.displayFormats.indexOf(String(this.screeningsRaw[screening][movie][language][theatre]['format'])) == -1) {
                                    continue;
                                }
                                if (!screenings[screening]) {
                                    screenings[screening] = {}
                                }
                                if (!screenings[screening][movie]) {
                                    screenings[screening][movie] = {}
                                }
                                if (!screenings[screening][movie][language]) {
                                    screenings[screening][movie][language] = {}
                                }
                                if (!screenings[screening][movie][language][theatre]) {
                                    screenings[screening][movie][language][theatre] = {}
                                }
                                screenings[screening][movie][language][theatre] = this.screeningsRaw[screening][movie][language][theatre]
                            }

                        }
                    }

                }
                return screenings
            }
        }
    }
</script>
