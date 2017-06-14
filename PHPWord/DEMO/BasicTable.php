<?php

class Domain_BasicTable {

    public function getBasicTable() {
        $PHPWord = new PHPWord_Lite();

        // New portrait section
        $section = $PHPWord->createSection();

        // Add table
        $table = $section->addTable();

        for($r = 1; $r <= 10; $r++) { // Loop through rows
            // Add row
            $table->addRow();

            for($c = 1; $c <= 5; $c++) { // Loop through cells
                // Add Cell
                $table->addCell(1750)->addText("Row $r, Cell $c");
            }
        }

// Save File
        $objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
        $objWriter->save('BasicTable.docx');
        return 'BasicTable is created!';
    }
}
