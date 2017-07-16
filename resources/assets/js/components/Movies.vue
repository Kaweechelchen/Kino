<template>
    <div class="MoviesContainer">
        <div class="col-sm-3">
            <theatre-select
                :displayTheatres="displayTheatres"
                v-on:updateTheatres="updateTheatres"></theatre-select>
            <language-select
                :displayLanguages="displayLanguages"
                v-on:updateLanguages="updateLanguages"></language-select>
        </div>
        <div class="col-sm-9">
            <screening
                v-for="(movies, screening) in screenings"
                :screening="screening"
                :movies="movies">
            </screening>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                theatres: theatres,
                languages: languages,
                screeningsRaw: screenings,
                displayTheatres: [
                    'UCUTO',
                    'CSTAR',
                    'UBVAL',
                    'UKRCH'
                ],
                displayLanguages: [
                    '302',
                    '305',
                    '313',
                    '727',
                    '728',
                    '730'
                ],
                displayFormats: [
                    '497',
                    '500'
                ]
            }
        },
        methods: {
            updateTheatres: function(displayTheatres) {
                localStorage.setItem('displayTheatres', JSON.stringify(displayTheatres))
                this.displayTheatres = displayTheatres
            },
            updateLanguages: function(displayLanguages) {
                localStorage.setItem('displayLanguages', JSON.stringify(displayLanguages))
                this.displayLanguages = displayLanguages
            }
        },
        computed: {
            screenings: function() {
                var screenings = {};
                for (var screening in this.screeningsRaw) {
                    for (var movie in this.screeningsRaw[screening]) {
                        for (var theatre in this.screeningsRaw[screening][movie]) {
                            if (this.displayTheatres.indexOf(theatre) == -1) {
                                continue;
                            }
                            for (var language in this.screeningsRaw[screening][movie][theatre]) {
                                if (this.displayLanguages.indexOf(language) == -1) {
                                    continue;
                                }

                                if (this.displayFormats.indexOf(String(this.screeningsRaw[screening][movie][theatre][language]['format'])) == -1) {
                                    continue;
                                }
                                if (!screenings[screening]) {
                                    screenings[screening] = {}
                                }
                                if (!screenings[screening][movie]) {
                                    screenings[screening][movie] = {}
                                }
                                if (!screenings[screening][movie][theatre]) {
                                    screenings[screening][movie][theatre] = {}
                                }
                                if (!screenings[screening][movie][theatre][language]) {
                                    screenings[screening][movie][theatre][language] = {}
                                }
                                screenings[screening][movie][theatre][language] = this.screeningsRaw[screening][movie][theatre][language]
                            }

                        }
                    }

                }
                return screenings
            }
        },
        mounted: function () {
            if (localStorage.getItem('displayLanguages'))
                this.displayLanguages = JSON.parse(localStorage.getItem('displayLanguages'))
            if (localStorage.getItem('displayTheatres'))
                this.displayTheatres = JSON.parse(localStorage.getItem('displayTheatres'))
        }
    }
</script>
