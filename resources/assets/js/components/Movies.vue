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
                theatres: {
                    UCUTO: 'Ciné Utopia',
                    CSTAR: 'Ciné Starlight',
                    UBVAL: 'Kinepolis Belval',
                    UKRCH: 'Kinepolis Kirchberg',
                    CMAAC: 'Cinémaacher',
                    CORIO: 'Ciné Orion',
                    CPARI: 'Ciné Le Paris',
                    CPRAB: 'Ciné Prabbeli',
                    CSCDI: 'Ciné Scala Diekirch',
                    CSURA: 'Ciné Sura'
                },
                displayTheatres: [
                    'UCUTO',
                    'CSTAR',
                    'UBVAL',
                    'UKRCH'
                ],
                languages: {
                    '302': 'Original version (Subtitles: French/German)',
                    '305': 'Original version (Subtitles: English)',
                    '313': 'Original version (Subtitles: French)',
                    '727': 'German version',
                    '728': 'French version',
                    '730': 'Original version',
                    '728_305': 'French version (Subtitles: English)',
                    '730_305': 'Original version (Subtitles: English)',
                    '730_313': 'Original version (Subtitles: French)',
                    '730_313-302': 'Original version (Subtitles: French/German)',
                    '730_393-313': 'Original version (Subtitles: Dutch/French'
                },
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
                screeningsRaw: {
                    "2017-07-15 13:45:00": {
                        "45762": {
                            "728": {
                                "UKRCH": {
                                    "hall": "6",
                                    "format": 497
                                }
                            },
                            "727": {
                                "UKRCH": {
                                    "hall": "2",
                                    "format": 497
                                },
                                "CSCDI": {
                                    "hall": "4",
                                    "format": 500
                                }
                            },
                            "730_313-302": {
                                "UKRCH": {
                                    "hall": "10",
                                    "format": 500
                                }
                            }
                        },
                        "45550": {
                            "727": {
                                "UKRCH": {
                                    "hall": "1",
                                    "format": 497
                                }
                            }
                        },
                        "45303": {
                            "727": {
                                "UKRCH": {
                                    "hall": "3",
                                    "format": 497
                                }
                            }
                        }
                    },
                    "2017-07-15 14:00:00": {
                        "45762": {
                            "727": {
                                "UBVAL": {
                                    "hall": "4",
                                    "format": 497
                                }
                            },
                            "728": {
                                "UBVAL": {
                                    "hall": "3",
                                    "format": 500
                                }
                            }
                        },
                        "45912": {
                            "730_393-313": {
                                "UCUTO": {
                                    "hall": "4",
                                    "format": 497
                                }
                            }
                        },
                        "45761": {
                            "727": {
                                "CSCDI": {
                                    "hall": "1",
                                    "format": 497
                                },
                                "CSTAR": {
                                    "hall": "1",
                                    "format": 497
                                },
                                "CSURA": {
                                    "hall": "1",
                                    "format": 500
                                },
                                "UBVAL": {
                                    "hall": "1",
                                    "format": 500
                                }
                            }
                        },
                        "45317": {
                            "727": {
                                "CSCDI": {
                                    "hall": "3",
                                    "format": 497
                                }
                            }
                        },
                        "45320": {
                            "727": {
                                "CMAAC": {
                                    "hall": "1",
                                    "format": 497
                                }
                            }
                        },
                        "45410": {
                            "727": {
                                "CSTAR": {
                                    "hall": "2",
                                    "format": 497
                                }
                            }
                        }
                    }
                }
            }
        },
        methods: {
            updateTheatres: function(displayTheatres) {
                console.log(displayTheatres)
                this.displayTheatres = displayTheatres
            },
            updateLanguages: function(displayLanguages) {
                console.log(displayLanguages)
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
