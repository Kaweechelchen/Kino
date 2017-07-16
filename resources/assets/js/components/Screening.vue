<template>
    <div class="ScreeningContainer">
        {{ screening | displayScreeningTime }}
        <movie
            v-for="(versions, movie) in movies"
            :movie="movie"
            :versions="versions">
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
                if (moment().diff(moment(date), 'days') == 0) {
                    return moment(date).format('HH:mm');
                }
                return moment(date).format('D.MM, HH:mm');
            }
        },
    }
</script>
