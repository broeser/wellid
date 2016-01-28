<?php

/*
 * These are all examples that are used within the README.md. This is, however
 * NOT the README itself. It is recommended to read the README first or in
 * parallel while looking at this code.
 */

// Autoloader, provided by composer
// Configure in composer.json
require_once __DIR__.'/vendor/autoload.php';

/**
 * SECTION 1
 * Simple use case _with validateBool()_
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
 * The simplest usage case is creating a new validator and using the 
 * __validateBool()__-method. It takes the value that shall be validated as parameter
 * and returns true on success and false on failure.
 */

/**
 * SECTION 2
 * Error handling _with validate() and ValidationResult_
 */

/*
 * Sometimes it is important to know, _why_ validation failed. If you need more than
 * boolean true/false, you can use the __validate()__-method of a validator of
 * your choice to get a __ValidationResult__-object. A ValidationResult includes 
 * an error message and error code if validation fails, you can use __getCode()__ and
 *  __getMessage()__ to retrieve them:
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
 * Validate the value and get a ValidationResult-object
 */
$validationResult = $maxLengthValidator->validate($value);

if($validationResult->hasPassed()) {
    print('The given value '.$value.' fits our requirements of a maximum length of 7 characters! YAY!'.PHP_EOL);
} else {
    print('The given value '.$value.' is totally invalid. :-('.PHP_EOL);
    print('Reason: '.$validationResult->getMessage().' – Error code: '.$validationResult->getCode().PHP_EOL);
}

/**
 * SECTION 3
 * 
 * ValidationResultSets _– A collection of ValidationResults_
 */

/**
 * If you validate a value with several different validators, or if you validate a
 * lot of different values you might need a better way of handling the results:
 * You can combine several ValidationResults to form a ValidationResultSet. You can
 * __add()__ a single ValidationResult to a ValidationResultSet or you can combine
 * two ValidationResultSets with __addSet()__
 * ValidationResultSets can be count()-ed and iterated over with foreach(). 
 * __hasErrors()__ is useful to check if there are any errors
 * in the ValidationResultSet, its counterpart is called __hasPassed()__. You can 
 * retrieve the __firstError()__-ValidationResult (if there is any), if everything
 * has passed the method will return null.
 */

/*
 * The value to validate
 */
$value = 'somethingentered';

/*
 * The validator that shall be used to validate it
 */
$maxLengthValidator = new Wellid\Validator\MaxLength(7);
$minLengthValidator = new Wellid\Validator\MinLength(3);

$validationResultSet = new Wellid\ValidationResultSet();
$validationResultSet->add($maxLengthValidator->validate($value));
$validationResultSet->add($minLengthValidator->validate($value));

if($validationResultSet->hasPassed()) {
    print('The given value '.$value.' fits our requirements of a maximum and minimum length! YAY!'.PHP_EOL);
} else {
    print('The given value '.$value.' is totally invalid. :-('.PHP_EOL);
}

/**
 * SECTION 4
 * 
 * Validating objects _with ValidatableTrait and ValidatableInterface_
 */

/**
 * If you want to create classes (as opposed to primitive data types) whose instances
 * are validateable by wellid, just implement the __ValidatableInterface__ in your class
 * and use the __ValidatableTrait__. You'll now be able to __addValidators()__ to your 
 * object. You can validate your object with the __validate()__ and __validateBool()__
 * methods. Make sure to implement a __getValue()__ method to supply the validators
 * with a primitive version of your object's value.
 * Note that __validate()__ returns a ValidationResultSet and not a ValidationResult
 * (see above).
 * 
 * The AccountBalance-class can be found in examples/AccountBalance.php. 
 * 
 * The example uses three validators: The value shall be a floating point number. 
 * It shall be between 0 (zero) and 830.
 */

foreach(array(57.3, -6) as $v) {
    $yourBalance = WellidUsageExamples\AccountBalance::createFromFloat($v);
    $result = $yourBalance->validate();
    if($result->hasErrors()) {
        print('Oh dear! Something invalid was used as my account balance!'.PHP_EOL);
        print('Aha, that is why: '.$result->firstError()->getMessage().PHP_EOL);
    }
}

/**
 * SECTION 6
 * 
 * A collection of Validators _The ValidatorHolder_*/

/*
 * The way wellid is designed, you can always add validators directly to your data
 * objects. However it might become handy to store a collection of validators
 * separate from the data objects or even without having data objects.
 * You can use the __ValidatorHolder__-class directly, extend it by your own class,
 * or you can create a class that implements __ValidatorHolderInterface__ and may
 * use the __ValidatorHolderTrait__ to get some basic functionality.
 * Adding validators works the same as on data objects: You
 * can use addValidators() or __addValidator()__ (for a single validator). Use 
 * __getValidators()__ to retrieve an array of all assigned validators.
 * To validate a value with the ValidatorHolder, use the __validateValue()__-method. 
 * The AccountBalance-example from above becomes much cleaner and easier to 
 * understand. The following example code can be found in 
 * examples/AccountBalanceValidators.php. (Of course you don't have to setup the
 * validators in the constructor in your own project, but you can just call
 * addValidator or addValidators from anywhere.)
 */

$accountBalanceValidators = new \WellidUsageExamples\AccountBalanceValidators();
foreach(array(57.3, -6) as $v) {
    $result = $accountBalanceValidators->validateValue($v);
    if($result->hasErrors()) {
        print('Oh dear! Something invalid was used as my account balance!'.PHP_EOL);
        print('Aha, that is why: '.$result->firstError()->getMessage().PHP_EOL);
    }
}

/**
 * Please note, that there is currently no validateBoolValue()-method for
 * ValidatorHolders. If you need the boolean value you can use the syntax
 *    validateValue($value)->hasPassed()
 * Another example shows, how to use ValidatorHolders with data objects. (Both 
 * used classes are the same as in the examples above):
 */

$accountBalanceValidators = new \WellidUsageExamples\AccountBalanceValidators();
foreach(array(57.3, -6) as $v) {
    $yourBalance = WellidUsageExamples\AccountBalance::createFromFloat($v);
    $result = $accountBalanceValidators->validateValue($yourBalance->getValue());
    if($result->hasErrors()) {
        print('Oh dear! Something invalid was used as my account balance!'.PHP_EOL);
        print('Aha, that is why: '.$result->firstError()->getMessage().PHP_EOL);
    }
}

/**
 * SECTION 7
 * 
 * Using wellid with Sanitor _– the SanitorBridgeTrait_
 */

/*
 * Sanitor is a thin wrapper around PHP's sanitization functions (filter_var(), 
 * filter_input(), etc.). If you want to use Sanitor to sanitize input or arbitrary
 * values before validating them with wellid, there is a handy piece of code called
 * the __SanitorBridgeTrait__ just for that.
 * Refer to the README.md in the broeser/sanitor-package for more information.
 * These are the four basic steps necessary to integrate Sanitor and wellid
 * 1. Install the Sanitor package (composer require broeser/sanitor)
 * 2. Substitute the ValidatableTrait and CacheableValidatableTraits with the 
 *    __SanitorBridgeTrait__ in all places.
 * 3. Make sure that those classes implement __SanitorBridgeInterface__
 * 4. Make sure that those classes call $this->setSanitizer(...) somewhere
 *    before validation (e. g. in the constructor) and set a fitting
 *    sanitization filter (you can try FILTER_DEFAULT)
 * The following example code can be found in examples/SanitorWellidEmailExample.php. 
 */

$emailValidator = new WellidUsageExamples\SanitorWellidEmailExample(new \Sanitor\Sanitizer(FILTER_SANITIZE_EMAIL));
$emailValidator->setRawValue('mail@benedict\roeser.de'); // because the value will be sanitized before validation, this will actually pass! More information in the "65{"-example below
if($emailValidator->validate()->hasErrors()) {
    print('Why! Oh why! Errors everywhere!'.PHP_EOL);
}

// values can also be optained from INPUT_GET, INPUT_POST, etc.:
$emailValidator->rawValueFromInput(INPUT_REQUEST, 'email');
if($emailValidator->validate()->hasPassed()) {
    print('Nice, this input has been valid: '.$emailValidator->getValue().PHP_EOL);
}

/**
 * Imagine you are expecting an integer as value, but the user enters `65{` instead.
 * Depending on your business logic, two different cases are possible:
 *  
 *  1. Ignore the { and assume 65, continue working with 65. Optionally notify the
 *     user of this – this makes sense if there is an undo anyway and you don't
 *     want to annoy your users with error messages.
 *  2. Return an error message and ask for the value again – this makes sense if
 *     the user is not expected to notice/fix the mistake, or if valid data is
 *     more important than user experience
 * The first case is already possible with the example above. For the second case,
 * just add a __SanitorMatch__-Validator to your object before starting validation.
 * The SanitorMatch-Validator expects the validatable object itself. It uses
 * "The given value contains illegal characters" as error message, if validation
 * fails.
 */
$emailValidator->addValidator(new \Wellid\Validator\SanitorMatch($emailValidator));

/**
 * Luckily there is a shorter way to accomplish the same with 
 * __addSanitorMatchValidator()__:
 */
$emailValidator->addSanitorMatchValidator();