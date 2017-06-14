<?php

class Domain_Template {

    public function getTemplate() {
        $PHPWord = new PHPWord_Lite();

        $document = $PHPWord->loadTemplate('Template.docx');

        $document->setValue('Value1', 'Sun');
        $document->setValue('Value2', 'Mercury');
        $document->setValue('Value3', 'Venus');
        $document->setValue('Value4', 'Earth');
        $document->setValue('Value5', 'Mars');
        $document->setValue('Value6', 'Jupiter');
        $document->setValue('Value7', 'Saturn');
        $document->setValue('Value8', 'Uranus');
        $document->setValue('Value9', 'Neptun');
        $document->setValue('Value10', 'Pluto');

        $document->setValue('weekday', date('l'));
        $document->setValue('time', date('H:i'));

        $document->save('Solarsystem.docx');
        return 'Template is created!';
    }
}
