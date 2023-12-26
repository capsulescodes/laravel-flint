<?php

namespace App\Contracts;


interface PathsRepository
{
    public function dirty() : array;
}
