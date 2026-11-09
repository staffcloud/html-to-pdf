<?php

require getenv('HTML_TO_PDF_AUTOLOAD') ?: dirname(__DIR__) . '/vendor/autoload.php';

use Spiritix\HtmlToPdf\Converter;
use Spiritix\HtmlToPdf\ConverterException;
use Spiritix\HtmlToPdf\Input\StringInput;
use Spiritix\HtmlToPdf\Output\StringOutput;

class ArchitectureProbeConverter extends Converter
{
    public $architecture;
    public $renderCommand;

    protected function executeShellCommand($command, $input = '')
    {
        if ($command === 'uname -m') {
            return ['output' => $this->architecture . "\n", 'error' => '', 'result' => 0];
        }

        $this->renderCommand = $command;

        if ($input !== '<html><body>Architecture fixture</body></html>') {
            throw new RuntimeException('The renderer did not receive the original HTML');
        }

        return ['output' => '%PDF-fixture', 'error' => '', 'result' => 0];
    }
}

$input = new StringInput();
$input->setHtml('<html><body>Architecture fixture</body></html>');

foreach ([
    'aarch64' => '/bin/linux-arm64/wkhtmltopdf',
    'arm64' => '/bin/linux-arm64/wkhtmltopdf',
    'x86_64' => '/' . Converter::PATH_BINARY_AMD,
    'amd64' => '/' . Converter::PATH_BINARY_AMD,
    'i386' => '/' . Converter::PATH_BINARY_INTEL,
    'i486' => '/' . Converter::PATH_BINARY_INTEL,
    'i586' => '/' . Converter::PATH_BINARY_INTEL,
    'i686' => '/' . Converter::PATH_BINARY_INTEL,
] as $architecture => $suffix) {
    $converter = new ArchitectureProbeConverter($input, new StringOutput());
    $converter->architecture = $architecture;
    $converter->setOptions([
        'margin-top' => '25',
        'header-html' => 'https://example.invalid/header?fixture=one&part=two',
    ]);

    if ($converter->convert()->get() !== '%PDF-fixture') {
        throw new RuntimeException('PDF output changed for ' . $architecture);
    }

    if (strpos($converter->renderCommand, $suffix . "' ") === false
        || strpos($converter->renderCommand, "--margin-top '25'") === false
        || strpos($converter->renderCommand, "--header-html 'https://example.invalid/header?fixture=one&part=two'") === false) {
        throw new RuntimeException('Incorrect binary or options for ' . $architecture);
    }
}

foreach (['', 'ppc64le', 'armv7l'] as $architecture) {
    $converter = new ArchitectureProbeConverter($input, new StringOutput());
    $converter->architecture = $architecture;

    try {
        $converter->convert();
        throw new RuntimeException('Unsupported architecture reached the renderer');
    } catch (ConverterException $exception) {
        if ($converter->renderCommand !== null) {
            throw new RuntimeException('Unsupported architecture reached the renderer');
        }
    }
}

echo "Architecture selection, HTML, options and output checks passed.\n";
