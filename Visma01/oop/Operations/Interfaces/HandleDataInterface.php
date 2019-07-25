<?php


namespace Algorithm\Interfaces;

/**
 * Handles data, can work with cache or database or text files
 *
 */

interface HandleDataInterface
{
    public function save();

    public function retrieve();
}