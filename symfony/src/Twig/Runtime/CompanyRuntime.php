<?php

namespace App\Twig\Runtime;

use App\Entity\Company;
use Twig\Extension\RuntimeExtensionInterface;

class CompanyRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function doSomething($value)
    {
        if($value instanceof Company){
            return '<b style="color:green;">'.$value->getName().'</b>';
        }
        return "<b>No Company</b>";
    }
}
