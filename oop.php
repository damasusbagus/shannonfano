<?php

class GrandPas   // The Grandfather's class
{
    public     $name1 = 'Mark Henry';  // This grandpa is mapped to a public modifier
    protected  $name2 = 'John Clash';  // This grandpa is mapped to a protected  modifier
    private    $name3 = 'Will Jones';  // This grandpa is mapped to a private modifier
}


# Scenario 1: without reflection
$granpaWithoutReflection = new GrandPas;

# Normal looping to print all the members of this class
echo "#Scenario 1: Without reflection<br>";
echo "Printing members the usual way.. (without reflection)<br>";
foreach($granpaWithoutReflection as $k=>$v)
{
    echo "The name of grandpa is $v and he resides in the variable $k<br>";
}

echo "<br>";

#Scenario 2: Using reflection

$granpa = new ReflectionClass('GrandPas'); // Pass the Grandpas class as the input for the Reflection class
$granpaNames=$granpa->getDefaultProperties(); // Gets all the properties of the Grandpas class (Even though it is a protected or private)


echo "#Scenario 2: With reflection<br>";
echo "Printing members the 'reflect' way..<br>";

foreach($granpaNames as $k=>$v)
{
    echo "The name of grandpa is $v and he resides in the variable $k<br>";
}