<?php
namespace core;

/**
 *Main View Class
 */
class View
{
    public function generate($contentView, $templateView, $data = null)
    {
        include 'app/Views'. $templateView;
    }
}
