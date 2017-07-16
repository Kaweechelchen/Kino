<template>
    <div class="ScreeningContainer">
        {{ screening | displayScreeningTime }}
        <movie
            v-for="(theatres, movie) in movies"
            :movie="movie"
            :theatres="theatres">
        </movie>
    </div>
</template>

<script>
    export default {
        name: 'screening',
        props: [
            'screening',
            'movies'
        ],
        filters: {
            displayScreeningTime: function (date) {
                if (moment().isSame(moment(date), 'd')) {
                    return moment(date).format('HH:mm');
                }
                return moment(date).format('dddd, D.MM, HH:mm');
            }
        },
    }
</script>
