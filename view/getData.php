<?php

    $preUrl = 'http://www.sharewire.net/mc2/v2.0/api/index.php?mc=54&v=0.1&params=country=lu--language=en&item=';
    $types  = array( 'showtimes', 'movieinfo', 'cinemainfo' );

    function xml2json( $url, $type ) {

        $fileContents= file_get_contents($url);

        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);

        $fileContents = trim(str_replace('"', "'", $fileContents));

        $simpleXml = simplexml_load_string($fileContents);

        $json = json_encode($simpleXml);

        file_put_contents($type.'.json', $json);

    }

    foreach ( $types as $type ) {
        xml2json( $preUrl.$type, $type );
    }