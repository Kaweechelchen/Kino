<template>
    <div class="MoviesContainer row">
        <div class="col-sm-3 col-lg-2 sideBar">
            <h1 class="border-bottom">Movies 🍿</h1>
            <theatre-select
                :displayTheatres="displayTheatres"
                class="border-bottom"
                v-on:updateTheatres="updateTheatres">
            </theatre-select>
            <language-select
                :displayLanguages="displayLanguages"
                class="border-bottom"
                v-on:updateLanguages="updateLanguages">
            </language-select>
            <format-select
                :displayFormats="displayFormats"
                class="border-bottom"
                v-on:updateFormats="updateFormats">
            </format-select>
            <footer class="center hidden-xs">
                <a href="https://github.com/kaweechelchen/kino" target="_blank"><i title="coded" class="fa fa-code" aria-hidden="true"></i></a> with <i title="love" class="fa fa-heart red pulse" aria-hidden="true"></i> by <a href="https://twitter.com/FAQ">Tezza</a>
                <br />Logo made by <a href="http://www.flaticon.com/authors/dimi-kazak" target="_blank" title="Dimi Kazak">Dimi Kazak</a> from <a href="http://www.flaticon.com" target="_blank" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
                <br />Glasses icon created by Fabio Grande from the Noun Project
                <br />Data scraped from Kinepolis
            </footer>
        </div>
        <div class="col-sm-9 col-lg-10">
            <screening
                v-for="(movies, screening) in screenings"
                :screening="screening"
                :movies="movies">
            </screening>
        </div>
    </div>
</template>

<style lang="sass">
    input[type=checkbox]
        display: none
        box-sizing: border-box
        padding: 0

    label
        padding: 0.2em 0
        line-height: 1.1
        color: rgba(#636b6f,0.4)
        cursor: pointer
        transition: all 250ms ease-in-out

    input[type=checkbox]:checked + label
        color: #636b6f

    .sideBar
        border-right: 1px solid #eee
        height: 100%

    footer
        font-size: 0.7em

    .border-bottom
        padding-bottom: 0.5em
        margin: 0.5em 0
        border-bottom: 1px solid #eee

</style>

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
                    '497'
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
            },
            updateFormats: function(displayFormats) {
                localStorage.setItem('displayFormats', JSON.stringify(displayFormats))
                this.displayFormats = displayFormats
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
            if (localStorage.getItem('displayFormats'))
                this.displayFormats = JSON.parse(localStorage.getItem('displayFormats'))
        }
    }
</script>
