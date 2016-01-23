# wellid

wellid is a set of PHP validators and a few loosely coupled components for validation.


## Goals

- wellid should be easy to use and easy to learn
- It is up to you, how much of the wellid-package you use, you can start with
  small building blocks and use bigger concepts later

## Installation

The package is called broeser/wellid and can be installed via composer:

composer require broeser/wellid

## How to use

**IMPORTANT NOTE:** Never try to validate raw data! Sanitize your data first, 
then pass it to wellid. Recommended sanitization options are:

 1. Let your framework handle sanitization
 2. Use Sanitor (composer require broeser/sanitor)
 3. Use PHP's filter_input() and filter_var() methods

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
 - Float
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
    print('Reason: '.$validationResult->getMessage().' – Error code: '.$validationResult->getCode());
}
```

### ValidationResultSets _– A collection of ValidationResults_

If you validate a value with several different validators, or if you validate a
lot of different values you might need a better way of handling the results:

You can combine several ValidationResults to form a ValidationResultSet. You can
**add()** a single ValidationResult to a ValidationResultSet or you can combine
two ValidationResultSets with **addSet()**

ValidationResultSets can be count()-ed and iterated over with foreach(). 

**hasErrors()** are useful to check if there are any errors
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
$minLengthValidator = new Wellid\Validator\MaxLength(3);

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
and use the **ValidatableTrait**. You'll now be able to **addValidators()** to your 
object. You can validate your object with the **validate()** and **validateBool()**
methods. Make sure to implement a **getValue()** method to supply the validators
with a primitive version of your object's value.

Note that **validate()** returns a ValidationResultSet and not a ValidationResult
(see above).

The following example code can be found in usage_examples.php and examples/AccountBalance.php. 
It uses three validators: The value shall be a floating point number. It shall
be at least 0 (zero). Supplying a value is required. (The required validator
needs to know the primitive data type, 'numeric').

```PHP
<?php
class AccountBalance implements \Wellid\ValidatableInterface {
    use \Wellid\ValidatableTrait;

    /**
     * @var float
     */
    protected $value = null;

    public function __construct() {
        $this->addValidators(new \Wellid\Validator\Float(), new \Wellid\Validator\Min(0), new \Wellid\Validator\Required('numeric'));
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

### A collection of Validators _with ValidatorHolderInterface and ValidatorHolderTrait_

The way wellid is designed, you can always add validators directly to your data
objects. However it might become handy to store a collection of validators
separate from the data objects or even without having data objects.

To implement a collection of validators, create a class and let it implement
**ValidatorHolderInterface**. Use the **ValidatorHolderTrait** to get some
basic functionality. Adding validators works the same as on data objects: You
can use addValidators() or **addValidator()** (for a single validator). Use 
**getValidators()** to retrieve an array of all assigned validators.

To validate a value with the ValidatorHolder you implemented, use the 
**validateValue()**-method. 

The AccountBalance-example from above becomes much cleaner and easier to 
understand. The following example code can be found in usage_examples.php and 
examples/AccountBalanceValidators.php. (Of course you don't have to setup the
validators in the constructor in your own project, but you can just call
addValidator or addValidators from anywhere.)

```PHP
<?php
class AccountBalanceValidators implements \Wellid\ValidatorHolderInterface {
    use \Wellid\ValidatorHolderTrait;

    public function __construct() {
        $this->addValidators(new \Wellid\Validator\Float(), new \Wellid\Validator\Min(0), new \Wellid\Validator\Required('numeric'));
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

Please note, that there is currently no validateBoolValue()-method for
ValidatorHolders. If you need the boolean value you can use the syntax
   validateValue($value)->hasPassed()

Another example shows, how to use ValidatorHolders with data objects. (Both 
used classes are the same as in the examples above):
```PHP
<?php
/*
 * Example 3b: Using the ValidatorHolderTrait & ValidatorHolderInterface with
 * data objects (see UsageExamples/-directory)
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

### Using wellid with Sanitor _– the SanitorBridgeTrait_

Sanitor is a thin wrapper around PHP's sanitization functions (filter_var(), 
filter_input(), etc.). If you want to use Sanitor to sanitize input or arbitrary
values before validating them with wellid, there is a handy piece of code called
the **SanitorBridgeTrait** just for that.

Refer to the README.md in the broeser/sanitor-package for more information.
These are the four basic steps necessary to integrate Sanitor and wellid

1. Install the Sanitor package (composer require broeser/sanitor)
2. Use the **SanitorBridgeTrait** in all places where you need sanitized data to
   be validated. Use it additionally to the ValidatableTrait.
3. Make sure that those classes (which should already implement 
   ValidatableInterface) also implement **SanitorBridgeInterface**
4. Make sure that those classes call $this->setSanitizer(...) somewhere
   before validation (e. g. in the constructor) and set a fitting
   sanitization filter (you can try FILTER_DEFAULT)

The following example code can be found in usage_examples.php and examples/SanitorWellidEmailExample.php. 

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
$emailValidator->setRawValue('mail@benedictroeser.de');
if($emailValidator->validate()->hasErrors()) {
    print('Why! Oh why! Errors everywhere!');
}

// values can also be optained from INPUT_GET, INPUT_POST, etc.:
$emailValidator->rawValueFromInput(INPUT_REQUEST, 'email');
if($emailValidator->validate()->hasPassed()) {
    print('Nice, this input has been valid: '.$emailValidator->getValue());
}
```

### Exceptions

Feel free to use the Exception-classes supplied with wellid in any validation
context you want to.

 - DataFormat
 - DataType
 - NotFound


## wellid?

Yes, it is a pun on well and valid.
