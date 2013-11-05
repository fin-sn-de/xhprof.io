<?php

require 'Bootstrap.php';

try {

    $e = new DataPersistancePerfEvent();
    $e->classSetUp();
    $e->persistProfile();
} catch (Exception $e) {
    var_dump($e->getMessage());
}
