<?php
namespace Infinity\Links;

interface LinkInterface
{
    public function getObject();

    public function getTitle();

    public function getNamespace();

    public function getId();

    public function getUrl();
}
