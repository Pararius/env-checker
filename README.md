# env-checker

[![Build Status](https://travis-ci.com/pararius/env-checker.svg?branch=master)](https://travis-ci.com/pararius/env-checker)
[![Packagist](https://img.shields.io/packagist/v/pararius/env-checker.svg)](https://packagist.org/packages/pararius/env-checker)
[![SemVer stability](https://api.dependabot.com/badges/compatibility_score?dependency-name=pararius/env-checker&package-manager=composer&version-scheme=semver)](https://dependabot.com/compatibility-score/?dependency-name=pararius/env-checker&package-manager=composer)

CLI tool to check if your k8s deployment implements all 
environment variables that your project specifies.

[![DEMO](/examples/demo.gif)](/examples/demo.gif)


## Requirements

- Machine with PHP installed (>= 7.2)
- You deploy your project on a Kubernetes cluster, 
  but maintain it's configuration in a separate repository.


## Documentation

- [Installation](docs/installation.md)
- [Usage](docs/usage.md)
- [Misc](docs/misc.md) (concepts, roadmap, reasoning, etc.)
- [Changelog](CHANGELOG.md)
