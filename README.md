# wellid

wellid is a set of PHP validators and a few loosely coupled components for validation.

[![Build Status](https://travis-ci.org/broeser/wellid.svg?branch=master)](https://travis-ci.org/broeser/wellid)
[![codecov.io](https://codecov.io/github/broeser/wellid/coverage.svg?branch=master)](https://codecov.io/github/broeser/wellid?branch=master)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://mit-license.org)
[![SemVer 2.0.0](https://img.shields.io/badge/semver-2.0.0-blue.svg)](http://semver.org/spec/v2.0.0.html)


## Goals

- wellid should be easy to use and easy to learn
- It is up to you, how much of the wellid-package you use, you can start with
  small building blocks and use bigger concepts later
- Extending wellid with your own Validators should be easy 

## Installation

wellid works with PHP 5.6 and 7.0.

The package can be installed via composer:

``composer require broeser/wellid``

## Before you start

- All examples from this manual can be found in [usage_examples.php](usage_examples.php)
  in the same order as in the manual. If an example uses an additional class,
  that class can be found in the examples/-directory.
- **IMPORTANT NOTE:** Never try to validate raw data! Sanitize your data first, 
then pass it to wellid. Recommended sanitization options are:
  1. Let your framework handle sanitization
  2. Use [Sanitor](https://github.com/broeser/sanitor) (composer require broeser/sanitor)
  3. Use PHP's filter_input() and filter_var() methods

## Using wellid

### Simple use case _with validateBool()_

```PHP
<?php
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
```

The simplest usage case is creating a new validator and using the 
**validateBool()**-method. It takes the value that shall be validated as parameter
and returns true on success and false on failure.

These are the validators supplied with wellid by default:

 - Boolean
 - Date
 - Email
 - Filesize _(experimental)_
 - FloatingPoint
 - IPAddress
 - Integer
 - MacAddress
 - Max
 - MaxLength
 - MIME _(experimental)_
 - Min
 - MinLength
 - Password
 - URL

### Error handling _with validate() and ValidationResult_

Sometimes it is important to know, _why_ validation failed. If you need more than
boolean true/false, you can use the **validate()**-method of a validator of
your choice to get a **ValidationResult**-object. A ValidationResult includes 
an error message and error code if validation fails, you can use **getCode()** and
 **getMessage()** to retrieve them:

```PHP
<?php
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
```

### ValidationResultSets _– A collection of ValidationResults_

If you validate a value with several different validators, or if you validate a
lot of different values you might need a better way of handling the results:

You can combine several ValidationResults to form a ValidationResultSet. You can
**add()** a single ValidationResult to a ValidationResultSet or you can combine
two ValidationResultSets with **addSet()**

ValidationResultSets can be count()-ed and iterated over with foreach(). 

**hasErrors()** is useful to check if there are any errors
in the ValidationResultSet, its counterpart is called **hasPassed()**. You can 
retrieve the **firstError()**-ValidationResult (if there is any), if everything
has passed the method will return null.

```PHP
<?php
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
```


### Validating objects _with ValidatableTrait and ValidatableInterface_

If you want to create classes (as opposed to primitive data types) whose instances
are validateable by wellid, just implement the **ValidatableInterface** in your class
and use the **ValidatableTrait**. If you prefer abstract classes instead, (e.g.
if you want to override functionality from ValidatableTrait), extending
**AbstractValidatable** is the way to go. In either case, Make sure to implement
 a **getValue()** method to supply the validators with a primitive typed version
of your object's value. For a Money-class, for example, that might be a float.

You'll now be able to **addValidators()** to your 
object. You can validate your object with the **validate()** and **validateBool()**
methods. 

Note that **validate()** returns a ValidationResultSet and not a ValidationResult
(see above).

The following example code uses three validators: 
The value shall be a floating point number. It shall be between 0 (zero) and 
830.

```PHP
<?php
class AccountBalance implements \Wellid\ValidatableInterface {
    use \Wellid\ValidatableTrait;

    /**
     * @var float
     */
    protected $value = null;

    public function __construct() {
        $this->addValidators(new \Wellid\Validator\FloatingPoint(), new \Wellid\Validator\Min(0), new \Wellid\Validator\Max(830));
    }
    
    /**
     * @param float $val
     * @return \self
     */    
    public static function createFromFloat($val) {
        $newInstance = new self();
        $newInstance->setValue($val);
        return $newInstance;
    }
    
    /**
     * @return float
     */
    public function getValue() {
        return $this->value;
    }
    
    /**
     * @param float $val
     */
    public function setValue($val) {
        $this->value = $val;
    }
}

foreach(array(57.3, -6) as $v) {
    $yourBalance = WellidUsageExamples\AccountBalance::createFromFloat($v);
    $result = $yourBalance->validate();
    if($result->hasErrors()) {
        print('Oh dear! Something invalid was used as my account balance!'.PHP_EOL);
        print('Aha, that is why: '.$result->firstError()->getMessage().PHP_EOL);
    }
}
```

### Caching ValidationResultSets _with CacheableValidatableTrait and CacheableValidatableInterface_

If a lot of Validators are used in validating an object, caching might improve
performance. In the last chapter each call to validate() or validateBool()
starts validation anew. To add caching functionality use 
**CacheableValidatableInterface** instead of ValidatableInterface and 
**CacheableValidatableTrait** instead of ValidatableTrait. If you prefer an
abstract class over traits and interfaces, extend **AbstractCacheableValidatable**.
All cache-related functionality can be found in the \Wellid\Cache-namespace.

Like the cacheless variant, CacheableValidatable has to implement the 
getValue()-method.

Caching will be performed automatically.

If you want to disable caching for a particular instance of 
CacheableValidatableInterface, you can call **disableValidationCache()**. This
will also remove a potentially existing ValidationResultSet from the cache of
that instance.

While rarely useful, you can also force revalidation without disabling the cache.
Clear the current ValidationResultSet with **clearValidationResult()**, then use
validate() or validateBool() to get a new ValidationResultSet.


### A collection of Validators _– The ValidatorHolder_

The way wellid is designed, you can always add validators directly to your data
objects. However it might become handy to store a collection of validators
separate from the data objects or even without having data objects.

You can use the **ValidatorHolder**-class directly, extend it by your own class,
or you can create a class that implements **ValidatorHolderInterface** and may
use the **ValidatorHolderTrait** to get some basic functionality.

Adding validators works the same as on data objects: You
can use addValidators() or **addValidator()** (for a single validator). Use 
**getValidators()** to retrieve an array of all assigned validators.

To validate a value with the ValidatorHolder, use the **validateValue()**-method. 

The AccountBalance-example from above becomes much cleaner and easier to 
understand. 

Example:

```PHP
<?php
class AccountBalanceValidators extends \Wellid\ValidatorHolder {
    public function __construct() {
        $this->addValidators(new \Wellid\Validator\FloatingPoint(), new \Wellid\Validator\Min(0), new \Wellid\Validator\Max(830));
    }
}

$accountBalanceValidators = new \WellidUsageExamples\AccountBalanceValidators();
foreach(array(57.3, -6) as $v) {
    $result = $accountBalanceValidators->validateValue($v);
    if($result->hasErrors()) {
        print('Oh dear! Something invalid was used as my account balance!'.PHP_EOL);
        print('Aha, that is why: '.$result->firstError()->getMessage().PHP_EOL);
    }
}
```
Of course you don't have to setup the validators in the constructor in your own 
project, but you can just call addValidator or addValidators from anywhere.

Please note, that there is currently no validateBoolValue()-method for
ValidatorHolders. If you need the boolean value you can use the syntax
``$validationResultAsBool = $accountBalanceValidators->validateValue($value)->hasPassed();``

Another example shows, how to use ValidatorHolders with data objects. (Both 
used classes are the same as in the examples above):
```PHP
<?php
/*
 * Example 3b: Using the ValidatorHolderTrait & ValidatorHolderInterface with
 * data objects
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
```

### Using wellid with Sanitor _with SanitorBridgeTrait and SanitorBridgeInterface_

[Sanitor](https://github.com/broeser/sanitor) is a thin wrapper around PHP's 
sanitization functions (filter_var(), filter_input(), etc.). 
If you want to use Sanitor to sanitize input or arbitrary
values before validating them with wellid, there is a handy piece of code called
the **SanitorBridgeTrait** just for that.

Refer to Sanitor's README.md for more information.
These are the four basic steps necessary to integrate Sanitor and wellid

1. Install the Sanitor package (composer require broeser/sanitor)
2. Substitute the ValidatableTrait and CacheableValidatableTraits with the 
   **SanitorBridgeTrait** in all places.
3. Make sure that those classes implement **SanitorBridgeInterface**
4. Make sure that those classes call $this->setSanitizer(...) somewhere
   before validation (e. g. in the constructor) and set a fitting
   sanitization filter (you can try FILTER_DEFAULT)

SanitorBridge automatically uses caching. You don't have to clear the cache
when setting a new rawValue, this will be done automatically.

```PHP
<?php
class SanitorWellidEmailExample implements \Wellid\SanitorBridgeInterface, \Wellid\ValidatableInterface {
    use \Wellid\SanitorBridgeTrait, \Wellid\ValidatableTrait;

    /**
     * Constructor
     * 
     * @param \Sanitor\Sanitizer $sanitizer
     */
    public function __construct(\Sanitor\Sanitizer $sanitizer = null) {
        $this->setSanitizer(is_null($sanitizer)?new \Sanitor\Sanitizer(FILTER_SANITIZE_EMAIL):$sanitizer);
        $this->addValidator(new \Wellid\Validator\Email());
    }
}

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
```

Imagine you are expecting an integer as value, but the user enters `65{` instead.
Depending on your business logic, two different cases are possible:
 
 1. Ignore the { and assume 65, continue working with 65. Optionally notify the
    user of this – this makes sense if there is an undo anyway and you don't
    want to annoy your users with error messages.
 2. Return an error message and ask for the value again – this makes sense if
    the user is not expected to notice/fix the mistake, or if valid data is
    more important than user experience

The first case is the default setting.

For the second case, just add a **SanitorMatch**-Validator to your object before
starting validation. The SanitorMatch-Validator expects the validatable object 
itself as parameter on construction. The Validator uses 
"The given value contains illegal characters" as error message, if validation
fails.

```PHP
<?php
$emailValidator->addValidator(new \Wellid\Validator\SanitorMatch($emailValidator));
```

Luckily there is a shorter way to accomplish the same with the method
**addSanitorMatchValidator()**:
```PHP
<?php
$emailValidator->addSanitorMatchValidator();
```

### Exceptions

Feel free to use the Exception-classes supplied with wellid in any validation
context you want to.

 - DataFormat
 - DataType
 - NotFound
 - FileNotFound

## Contributing?

Yes, please!

See [CONTRIBUTING.md](CONTRIBUTING.md) for details and/or open an issue with your questions.

Please note that this project is released with a [Contributor Code of Conduct](CODE_OF_CONDUCT.md). 
By participating in this project you agree to abide by its terms.

## wellid?

Yes, it is a pun on well and valid.
