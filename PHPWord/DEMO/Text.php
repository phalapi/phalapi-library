<?php

class Domain_Text {

    public function getText() {
        $PHPWord = new PHPWord_Lite();

// New portrait section
        $section = $PHPWord->createSection();

// Add text elements
        $section->addText('Hello World!');
        $section->addTextBreak(2);

        $section->addText('I am inline styled.', array('name'=>'Verdana', 'color'=>'006699'));
        $section->addTextBreak(2);

        $PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
        $PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
        $section->addText('I am styled by two style definitions.', 'rStyle', 'pStyle');
        $section->addText('I have only a paragraph style definition.', null, 'pStyle');



// Save File
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save('Text.docx');
        return 'Text is created!';
    }
}
