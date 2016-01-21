# wellid

wellid is a set of PHP validators and a few loosely coupled components for validation.


## Goals

- wellid should be easy to use and easy to learn

## Installation

The package is called broeser/wellid and can be installed via composer:

composer require broeser/wellid

## How to use

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

It is up to you, how much of the wellid-package you use: Either only the validators
  (in src/Validator) or the whole package including ValidationResultSets.

If you want to validate a value, use the **validate()**-method of a Validator of
your choice to get a ValidationResult-object (includes error message and error
code if validation fails) or the **validateBool()**-method to get a boolean.

If you want to create classes (as opposed to primitive data types) that are validateable,
just implement the ValidatableInterface in your class and use the ValidatableTrait. You'll
now be able to **addValidators()** to your class and to **validate()** it using those
validators.

## List of validators:

- Date
- Email
- Filesize
- Float
- Integer
- MIME
- Max
- MaxLength
- Min
- MinLength
- Password
- Required
- URL

## wellid?

Yes, it is a pun on well and valid.
