<?php
/* $Id: hello.php,v 1.17 2009/09/28 12:19:27 rjs Exp $
 *
 * PDFlib client: hello example in PHP
 */

try {
    $p = new PDFlib();

    # This means we must check return values of load_font() etc.
    $p->set_parameter("errorpolicy", "return");

    /* This line is required to avoid problems on Japanese systems */
    $p->set_parameter("hypertextformat", "utf8");
    $p->set_parameter("hypertextencoding", "unicode");

    /*  open new PDF file; insert a file name to create the PDF on disk */
    if ($p->begin_document("", "") == 0) {
	die("Error: " . $p->get_errmsg());
    }

    $p->set_info("Creator", "hello.php");
    $p->set_info("Author", "Rainer Schaaf");
    $p->set_info("Title", "Hello world (PHP)!");

    $p->begin_page_ext(595, 842, "");

    $font = $p->load_font("Helvetica-Bold", "winansi", "");
    if ($font == 0) {
	die("Error: " . $p->get_errmsg());
    }

    $p->setfont($font, 24.0);
    $p->set_text_pos(50, 700);
    $p->show("Hello world!");
    $p->continue_text("(says PHP)");

    $p->create_annotation( 78.600006, 646.799988, 244.200012, 471.599976, "Square", "name={vproof_annot_8611c1ac-204f-3c4d-6d8b-13e6da988001} contents={﻿© ™ ® ‡ † } title={Barratt, Shawn} annotcolor={rgb 1 1 0} createdate={true}");

    $p->end_page_ext("");

    $p->end_document("");

    $buf = $p->get_buffer();
    $len = strlen($buf);

    header("Content-type: application/pdf");
    header("Content-Length: $len");
    header("Content-Disposition: inline; filename=hello.pdf");
    print $buf;

}
catch (PDFlibException $e) {
    die("PDFlib exception occurred in hello sample:\n" .
	"[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
	$e->get_errmsg() . "\n");
}
catch (Exception $e) {
    die($e);
}

$p = 0;
?>
