<?php
/* 
 * The MIT License
 *
 * Copyright 2016 Benedict Roeser <b-roeser@gmx.net>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

// Autoloader, provided by composer
// Configure in composer.json
require_once __DIR__.'/vendor/autoload.php';


/*
 * Example 1: Using Validators
 */

/*
 * The value to validate
 */
$value = 'somethingentered';

/*
 * The validator that shall be used to validate it
 */
$maxLengthValidator = new Wellid\Validator\MaxLength(7);

/*
 * Validate the value and do something in case it is valid:
 */
if($maxLengthValidator->validateBool($value)) {
    print('The given value '.$value.' fits our requirements of a maximum length of 7 characters! YAY!'.PHP_EOL);
} else {
    print('The given value '.$value.' is totally invalid. :-('.PHP_EOL);
}



/*
 * Example 2: Using the ValidatableTrait & ValidatableInterface and ValidationResultSets
 * (see UsageExamples/-directory)
 */

foreach(array(57.3, -6) as $v) {
    $yourBalance = WellidUsageExamples\AccountBalance::createFromFloat($v);
    $result = $yourBalance->validate();
    if($result->hasErrors()) {
        print('Oh dear! Something invalid was used as my account balance!'.PHP_EOL);
        print('Aha, that is why: '.$result->firstError()->getMessage().PHP_EOL);
    }
}

// Refer to README.md for more information

if(!class_exists('\Sanitor\Sanitizer')) {
    die('Please install Sanitor for optional example 3: composer require broeser/sanitor'.PHP_EOL);
}

/*
 * (Optional) Example 3: wellid and Sanitor
 */

$emailValidator = new WellidUsageExamples\SanitorWellidEmailExample(new \Sanitor\Sanitizer(FILTER_SANITIZE_EMAIL));
$emailValidator->setRawValue('mail@benedictroeser.de');
if($emailValidator->validate()->hasErrors()) {
    print('Why! Oh why! Errors everywhere!');
}

// values can also be optained from INPUT_GET, INPUT_POST, etc.:
$emailValidator->rawValueFromInput(INPUT_REQUEST, 'email');
if($emailValidator->validate()->hasPassed()) {
    print('Nice, this input has been valid: '.$emailValidator->getValue());
}