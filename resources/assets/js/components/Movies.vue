<template>
    <div class="MoviesContainer row">
        <div class="col-sm-3 sideBar">
            <theatre-select
                :displayTheatres="displayTheatres"
                v-on:updateTheatres="updateTheatres">
            </theatre-select>
            <language-select
                :displayLanguages="displayLanguages"
                v-on:updateLanguages="updateLanguages">
            </language-select>
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

<style>
    input[type="checkbox"] {
        display: none;
        opacity: 0.5;
        box-sizing: border-box;
        padding: 0;
    }
    label {
        display: block;
        position: relative;
        padding: .5em 2.5em;
        line-height: 1.1;
        border: 1px solid #DDD;
        color: RGBa(0,0,0,0.5);
        cursor: pointer;
        border-radius: 3px;
        transition: all 250ms ease-in-out;
    }
    label:hover {
        background: #FFF;
        border-color: #CCC;
    }
    label:before {
        content: " ";
        position: absolute;
        top: .5em;
        left: .7em;
        width: 1em;
        height: 1em;
        background: #FFF;
        border: 1px solid #DDD;
        border-radius: 100%;
    }
    input[type="checkbox"]:checked + label {
        color: #333;
        background: #FFF;
    }
    input[type="checkbox"]:checked + label:before {
        background: #5CC886;
        border-color: #5CC886;
    }
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
