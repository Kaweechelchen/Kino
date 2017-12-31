<template>
    <div class="MoviesContainer row">
        <div class="col-12 title">
            <span class="left">
                <h1>Movies <img src="/img/logo.svg" style="width:1em" /></h1>
            </span>
            <span class="right" v-on:click="toggleMenu">
                <i class="fa fa-bars" aria-hidden="true" ></i>
            </span>
        </div>

        <!-- tablets & up -->
        <div class="col-lg-2 sideBar d-none d-lg-block d-xl-block">
            LARGE
            <theatre-select
                :displayTheatres="displayTheatres"
                class="border-bottom"
                v-on:updateTheatres="updateTheatres">
            </theatre-select>
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
        <div class="col-lg-10 offset-lg-2 screenings d-none d-lg-block d-xl-block">
            <screening
                v-for="(movies, screening) in screenings"
                :key="screening"
                :screening="screening"
                :movies="movies">
            </screening>
        </div>

        <!-- phones -->
        <div class="col-12 sideBar d-block d-lg-none d-xl-none"
            v-if="showMenu">
            SMALL
            <theatre-select
                :displayTheatres="displayTheatres"
                class="border-bottom"
                v-on:updateTheatres="updateTheatres">
            </theatre-select>
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
        <div class="col-12 screenings d-block d-lg-none d-xl-none"
            v-if="!showMenu">
            <screening
                v-for="(movies, screening) in screenings"
                :key="screening"
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
        position: fixed
        border-right: 1px solid #eee
        height: 100%
        overflow-y: scroll
        padding-top: 6em

    .screenings
        height: 100vh

    footer
        font-size: 0.7em

    .border-bottom
        padding-bottom: 0.5em
        margin: 0.5em 0
        border-bottom: 1px solid #eee

    .title
        padding: .1em 1em .3em
        border-bottom: 1px solid #eee
        background-color: #fff
        z-index: 10
        //background-color: rgb(252, 191, 49)

    .sideBar
        background-color: white

    .left
        float: left

    .right
        font-size: 2.5rem
        float: right

    @media (max-width: 991px)
        .title
            position: fixed
        .sideBar
            position: relative
            padding-top: 5em
        .screenings
            padding-top: 5em

    @media (min-width: 992px)
        .right
            display: none

</style>

<script>
    export default {
        data () {
            return {
                theatres: theatres,
                screeningsRaw: screenings,
                displayTheatres: [
                    'UCUTO',
                    'CSTAR',
                    'UBVAL',
                    'UKRCH'
                ],
                displayFormats: [
                    '497'
                ],
                showMenu: false
            }
        },
        methods: {
            updateTheatres: function(displayTheatres) {
                localStorage.setItem('displayTheatres', JSON.stringify(displayTheatres))
                this.displayTheatres = displayTheatres
            },
            updateFormats: function(displayFormats) {
                localStorage.setItem('displayFormats', JSON.stringify(displayFormats))
                this.displayFormats = displayFormats
            },
            toggleMenu: function(event) {
                console.log('blubb')
                this.showMenu = !this.showMenu
                console.log(this.showMenu)
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
                                screenings[screening][movie][theatre][language] = this.screeningsRaw[screening][movie][theatre][language]
                            }

                        }
                    }

                }
                return screenings
            }
        },
        mounted: function () {
            if (localStorage.getItem('displayTheatres'))
                this.displayTheatres = JSON.parse(localStorage.getItem('displayTheatres'))
            if (localStorage.getItem('displayFormats'))
                this.displayFormats = JSON.parse(localStorage.getItem('displayFormats'))
        }
    }
</script>
