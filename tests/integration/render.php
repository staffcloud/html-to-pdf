<?php

require getenv('HTML_TO_PDF_AUTOLOAD') ?: dirname(__DIR__, 2) . '/vendor/autoload.php';

use Spiritix\HtmlToPdf\Converter;
use Spiritix\HtmlToPdf\Input\UrlInput;
use Spiritix\HtmlToPdf\Output\StringOutput;

$outputDirectory = $argv[1];
$baseUrl = 'http://127.0.0.1:8080';

foreach (['offer', 'contract', 'template'] as $kind) {
    $input = new UrlInput();
    $input->setUrl($baseUrl . '/document.php?kind=' . $kind);
    $options = [
        'margin-top' => '25',
        'margin-bottom' => '20',
        'margin-left' => '0.001',
        'margin-right' => '0.001',
        'load-error-handling' => 'ignore',
        'header-html' => $baseUrl . '/header.html',
        'footer-html' => $baseUrl . '/footer.html',
    ];

    if ($kind === 'template') {
        $options = [
            'n',
            'margin-top' => '25',
            'margin-bottom' => '25',
            'header-spacing' => '5',
            'header-html' => $baseUrl . '/header.html',
            'footer-html' => $baseUrl . '/footer.html',
        ];
    }

    $converter = new Converter($input, new StringOutput(), $options);
    $pdf = $converter->convert()->get();

    if (substr($pdf, 0, 5) !== '%PDF-') {
        throw new RuntimeException('Invalid PDF for ' . $kind);
    }

    file_put_contents($outputDirectory . '/' . $kind . '.pdf', $pdf);
    echo $kind . ': ' . strlen($pdf) . " PDF bytes\n";
}
