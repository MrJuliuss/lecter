<?php

function lecter_path() {
    return preg_replace(sprintf('|%s|', $_SERVER['DOCUMENT_ROOT']), '', public_path());
}
