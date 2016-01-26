# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
- **CacheableValidatable…** that allow to cache ValidationResultSets
- **getErrorMessages()** retrieves an array of error message strings from a
  ValidationResultSet
- **SanitorMatch**-Validator, checks wheter given input stays the same, when it
  is sanitized
- Builds are now tested automatically by Travis CI
- Added CHANGELOG, CONTRIBUTING document and CODE_OF_CONDUCT

### Changed
- **MIME**-Validator now takes a filename string instead of an array containing
  this string

### Fixed
- **Boolean**-Validator now works correctly on objects and NULL
- Renamed former Float-Validator to **FloatingPoint**, in order to work on PHP 7
- **setSanitizeFlags()** 

## [0.2.0] - 2016-01-23
### Added
 - **Boolean**-Validator
 - **IPAddress**-Validator
 - **MacAddress**-Validator
 - **validateBool()**-method for Validatable…, works like validateBool() on
   Validators

### Fixed
 - **Integer**-Validator is working again

### Removed
 - **Required**-Validator

## [0.1.0] - 2016-01-22
### Added
- **firstError()**-method to get the first error in a ValidationResultSet
- **validateValue($v)**-method in Validatable… to validate specific values
- Adapter **SanitorBridge…**: Optional possibility to use wellid in conjunction 
  with Sanitor


## [0.0.2] - 2016-01-22
### Fixed
- **Date**-Validator was not working due to namespaceing issues

## [0.0.1] - 2016-01-21
- Initial release

[Unreleased]: https://github.com/broeser/wellid/compare/0.2.0...HEAD
[0.2.0]: https://github.com/broeser/wellid/releases/tag/0.2.0
[0.1.0]: https://github.com/broeser/wellid/releases/tag/0.1.0
[0.0.2]: https://github.com/broeser/wellid/releases/tag/0.0.2
[0.0.1]: https://github.com/broeser/wellid/releases/tag/0.0.1