{sourser}
<?php
$FILES = array(
    array(
        'desc' => 'Описание первой ссылки',
        'url' => 'https://download.me'
    ),
    array(
        'desc' => 'Описание второй ссылки',
        'url' => 'https://download.me/smth.pdf'
    )
);

function print_html( $arr ) {
    $format_html = '<div>${desc}<div>'.
        '<div><a download href="${url}">Скачать</a></div>';

    return preg_replace( array( '/\${desc}/', '/\${url}/' ),
        array( $arr['desc'], $arr['url'] ),
        $format_html );
}
?>

<div><?php
    foreach ( $FILES as $file ) print_html( $file );
?></div>
{/sourser}
